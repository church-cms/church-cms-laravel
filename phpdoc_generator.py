#!/usr/bin/env python3
"""
Script to analyze Laravel models and generate proper PhpDoc blocks
following Laravel and Open Source standards.
"""

import os
import re
from pathlib import Path
from typing import Dict, List, Tuple

class PhpDocGenerator:
    def __init__(self, models_path: str):
        self.models_path = Path(models_path)
        self.files = sorted(self.models_path.glob("*.php"))

    def get_class_name(self, content: str) -> str:
        """Extract class name from PHP file."""
        match = re.search(r'class\s+(\w+)', content)
        return match.group(1) if match else "Unknown"

    def get_namespace(self, content: str) -> str:
        """Extract namespace from PHP file."""
        match = re.search(r'namespace\s+([\w\\]+);', content)
        return match.group(1) if match else ""

    def get_traits(self, content: str) -> List[str]:
        """Extract used traits."""
        return re.findall(r'use\s+([\w\\]+);', content)

    def get_relationships(self, content: str) -> List[Tuple[str, str]]:
        """Extract relationship methods."""
        pattern = r'public\s+function\s+(\w+)\(\)\s*\n\s*\{\s*return\s+\$this->(\w+)\('
        matches = re.findall(pattern, content, re.MULTILINE)
        return matches

    def get_properties(self, content: str) -> Dict[str, str]:
        """Extract model properties."""
        properties = {}

        # Find $table
        match = re.search(r"protected\s+\$table\s*=\s*['\"](\w+)['\"]", content)
        if match:
            properties['table'] = match.group(1)

        # Find $fillable
        match = re.search(r"protected\s+\$fillable\s*=\s*\[(.*?)\]", content, re.DOTALL)
        if match:
            properties['fillable'] = "mass assignable attributes"

        # Find $casts
        match = re.search(r"protected\s+\$casts\s*=", content)
        if match:
            properties['casts'] = "attribute castings"

        # Find $dates
        match = re.search(r"protected\s+\$dates\s*=", content)
        if match:
            properties['dates'] = "date attributes"

        # Find $hidden
        match = re.search(r"protected\s+\$hidden\s*=", content)
        if match:
            properties['hidden'] = "hidden attributes"

        return properties

    def generate_class_phpdoc(self, filename: str, class_name: str,
                             properties: Dict[str, str], relationships: List[Tuple[str, str]]) -> str:
        """Generate appropriate class PhpDoc block."""

        description = f"Represents {class_name} model in the Church CMS application."

        # Determine context based on class name and properties
        if class_name == "User":
            description = "User model representing system users with authentication and authorization capabilities."
        elif class_name == "Church":
            description = "Church model representing the main church entity with multiple relationships and configurations."
        elif class_name == "Post":
            description = "Post model representing user-generated posts with media support and nested comments."
        elif class_name == "Sermon":
            description = "Sermon model representing sermons with voting and linking capabilities."
        elif class_name == "Payment" in class_name or "Payment" in class_name:
            description = f"{class_name} model for payment processing and financial transactions."
        elif "Role" in class_name or "Permission" in class_name:
            description = f"{class_name} model for role-based access control."
        elif "Mail" in class_name or "Email" in class_name or "Campaign" in class_name:
            description = f"{class_name} model for email and marketing campaign management."

        phpdoc = f"""/**
 * {class_name} Model
 *
 * {description}
 *
 * @package App\\Models
 * @property int $id
"""

        # Add relationships documentation
        if relationships:
            phpdoc += " * @property-read \\Illuminate\\Database\\Eloquent\\Collection Relationships\n"

        phpdoc += " */\n"

        return phpdoc

    def analyze_file(self, filepath: Path) -> Dict:
        """Analyze a PHP model file."""
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()

        class_name = self.get_class_name(content)
        namespace = self.get_namespace(content)
        traits = [self.extract_trait_name(t) for t in self.get_traits(content)]
        relationships = self.get_relationships(content)
        properties = self.get_properties(content)

        # Check if class already has PhpDoc
        has_phpdoc = bool(re.search(r'/\*\*.*?\*/\s*class\s+' + class_name, content, re.DOTALL))

        return {
            'filename': filepath.name,
            'class_name': class_name,
            'namespace': namespace,
            'traits': traits,
            'relationships': relationships,
            'properties': properties,
            'has_phpdoc': has_phpdoc,
            'content': content,
            'filepath': filepath
        }

    def extract_trait_name(self, trait: str) -> str:
        """Extract short trait name."""
        return trait.split("\\")[-1]

    def generate_report(self):
        """Generate analysis report."""
        report = []
        report.append("=" * 80)
        report.append("LARAVEL MODEL DOCUMENTATION AUDIT REPORT")
        report.append("=" * 80)
        report.append("")

        files_with_phpdoc = 0
        files_without_phpdoc = 0

        for filepath in self.files:
            analysis = self.analyze_file(filepath)

            status = "✓" if analysis['has_phpdoc'] else "✗"
            if analysis['has_phpdoc']:
                files_with_phpdoc += 1
            else:
                files_without_phpdoc += 1

            report.append(f"{status} {analysis['class_name']:30} - {filepath.name}")

            if analysis['relationships']:
                rel_str = ", ".join([f"{r[0]}()" for r in analysis['relationships'][:3]])
                if len(analysis['relationships']) > 3:
                    rel_str += f" +{len(analysis['relationships']) - 3} more"
                report.append(f"    Relationships: {rel_str}")

            if analysis['traits']:
                report.append(f"    Traits: {', '.join(analysis['traits'][:3])}")

        report.append("")
        report.append("=" * 80)
        report.append(f"Summary: {files_with_phpdoc} models with PhpDoc, {files_without_phpdoc} without")
        report.append("=" * 80)

        return "\n".join(report)

if __name__ == "__main__":
    models_path = "/Users/karthick/Code/church-cms-laravel/app/Models"
    generator = PhpDocGenerator(models_path)
    print(generator.generate_report())
