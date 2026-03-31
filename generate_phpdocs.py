#!/usr/bin/env python3
"""
Comprehensive Laravel Model PhpDoc generator.
Reads all models and generates proper documentation following Laravel standards.
"""

import os
import re
from pathlib import Path
from typing import Dict, List, Tuple, Optional

class LaravelModelDocumentor:
    def __init__(self, models_path: str):
        self.models_path = Path(models_path)
        self.files = sorted(self.models_path.glob("*.php"))
        self.models_data = {}
        self.replacements = []

    def read_all_models(self):
        """Read all model files."""
        for filepath in self.files:
            with open(filepath, 'r', encoding='utf-8') as f:
                self.models_data[filepath.name] = f.read()

    def extract_class_info(self, filename: str, content: str) -> Dict:
        """Extract important information from a model class."""

        # Extract class name
        class_match = re.search(r'class\s+(\w+)', content)
        class_name = class_match.group(1) if class_match else "Unknown"

        # Check for existing class PhpDoc
        has_class_phpdoc = bool(re.search(r'/\*\*.*?\*/\s*class\s+' + class_name, content, re.DOTALL))

        # Extract traits
        traits = re.findall(r'use\s+([\w\\]+);', content)

        # Extract table name
        table_match = re.search(r"protected\s+\$table\s*=\s*['\"](\w+)['\"]", content)
        table = table_match.group(1) if table_match else None

        # Extract fillable properties
        fillable_match = re.search(r"protected\s+\$fillable\s*=\s*\[(.*?)\];", content, re.DOTALL)
        fillable = []
        if fillable_match:
            fillable_str = fillable_match.group(1)
            fillable = [attr.strip().strip("'\"") for attr in fillable_str.split(',') if attr.strip()]

        # Extract methods (especially relationships)
        methods = re.findall(r'public\s+function\s+(\w+)\s*\(', content)

        # Extract relationship methods
        relationships = {}
        for method in methods:
            pattern = rf'public\s+function\s+{method}\(\)\s*\n\s*\{{\s*return\s+\$this->(\w+)\('
            match = re.search(pattern, content, re.MULTILINE)
            if match:
                relationships[method] = match.group(1)

        # Detect features
        has_soft_deletes = 'SoftDeletes' in content
        has_media = 'HasMedia' in content
        has_api_tokens = 'HasApiTokens' in content

        return {
            'filename': filename,
            'class_name': class_name,
            'has_class_phpdoc': has_class_phpdoc,
            'traits': traits,
            'table': table,
            'fillable': fillable,
            'methods': methods,
            'relationships': relationships,
            'has_soft_deletes': has_soft_deletes,
            'has_media': has_media,
            'has_api_tokens': has_api_tokens
        }

    def generate_class_phpdoc(self, class_name: str, info: Dict) -> str:
        """Generate a proper class-level PhpDoc block."""

        # Create descriptive text based on class name and features
        descriptions = {
            'User': 'User model representing system users with authentication and authorization capabilities.',
            'Church': 'Church model representing the main church entity with multiple relationships and configurations.',
            'Post': 'Post model representing user-generated posts with media support and nested comments.',
            'Sermon': 'Sermon model representing sermons with voting and social interaction capabilities.',
            'Event': 'Event model representing church events with registration and attendance tracking.',
            'Group': 'Group model representing user groups within the church community.',
            'Page': 'Page model representing static and dynamic pages in the CMS.',
            'Campaign': 'Campaign model for email marketing and broadcasting campaigns.',
            'Permission': 'Permission model for role-based access control system.',
            'Role': 'Role model for managing user roles and permissions.',
            'Fund': 'Fund model for managing church funds and financial tracking.',
            'PrayerRequest': 'PrayerRequest model for managing prayer requests from the community.',
            'Activity': 'Activity model for tracking user activity and audit logging.'
        }

        # Get matching description or generate default
        description = descriptions.get(class_name)

        if not description:
            # Generate based on class name
            if 'Mail' in class_name or 'Email' in class_name:
                description = f'{class_name} model for email and communication management.'
            elif 'Payment' in class_name or 'Subscription' in class_name:
                description = f'{class_name} model for payment processing and subscription management.'
            elif 'Gallery' in class_name or 'Photo' in class_name or 'Media' in class_name:
                description = f'{class_name} model for managing media files and galleries.'
            elif 'Bible' in class_name:
                description = f'{class_name} model for Bible content management.'
            elif 'Contact' in class_name or 'Feedback' in class_name:
                description = f'{class_name} model for managing user feedback and contact information.'
            else:
                description = f'{class_name} model in the Church CMS application.'

        # Build property lines for relationships
        properties_doc = '@package App\\Models'

        if info['relationships']:
            properties_doc += '\n * \n * Relationships:'
            for method, return_type in list(info['relationships'].items())[:5]:
                properties_doc += f'\n *   - {method}() - {return_type} association'
            if len(info['relationships']) > 5:
                properties_doc += f'\n *   - and {len(info["relationships"]) - 5} more relationships'

        phpdoc = f"""/**
 * {class_name} Model
 *
 * {description}
 *
 * @package App\\Models
 * @property int $id Primary key
"""

        if info['table']:
            phpdoc += f" * @property string $table Database table name: '{info['table']}'\n"

        if info['has_soft_deletes']:
            phpdoc += " * @property \\Carbon\\Carbon|null $deleted_at Soft delete timestamp\n"

        phpdoc += f" * @property \\Carbon\\Carbon $created_at Record creation timestamp\n"
        phpdoc += f" * @property \\Carbon\\Carbon $updated_at Record update timestamp\n"

        if info['relationships']:
            phpdoc += " *\n * Relations:\n"
            for method, rel_type in list(info['relationships'].items())[:8]:
                phpdoc += f" * @property-read \\Illuminate\\Database\\Eloquent\\Collection|{rel_type} ${method}\n"

        phpdoc += " */\n"

        return phpdoc

    def generate_replacements(self):
        """Generate all replacement operations for multi_replace_string_in_file."""

        self.read_all_models()

        for filename, content in self.models_data.items():
            info = self.extract_class_info(filename, content)

            if not info['has_class_phpdoc']:
                # Generate new PhpDoc
                phpdoc = self.generate_class_phpdoc(info['class_name'], info)

                # Find the class declaration line
                class_pattern = f"class {info['class_name']}"

                # Create replacement: namespace/use statements -> namespace + phpdoc + class
                # Find where to insert the PhpDoc
                namespace_end_match = re.search(r'(?:^use.*?)(\n\nclass\s+)', content, re.DOTALL | re.MULTILINE)

                if namespace_end_match:
                    # There's namespace/uses before class
                    old_string = namespace_end_match.group(0)
                    # Extract just the class line part
                    class_start = old_string.rfind('\n')
                    before_class = old_string[:class_start + 1]
                    class_line = old_string[class_start + 1:]

                    new_string = before_class + phpdoc + class_line

                    # Get more context for uniqueness
                    context_start = max(0, content.find(old_string) - 50)
                    if context_start > 0:
                        old_string = content[context_start:context_start + len(old_string)]

                    self.replacements.append({
                        'filename': filename,
                        'class_name': info['class_name'],
                        'old_string': old_string,
                        'new_string': new_string
                    })

        return self.replacements

    def print_replacements(self):
        """Print replacement operations for review."""
        print(f"Generated {len(self.replacements)} replacement operations:")
        for i, replacement in enumerate(self.replacements, 1):
            print(f"{i}. {replacement['filename']} ({replacement['class_name']})")

if __name__ == "__main__":
    models_path = "/Users/karthick/Code/church-cms-laravel/app/Models"
    documentor = LaravelModelDocumentor(models_path)
    documentor.generate_replacements()
    documentor.print_replacements()
