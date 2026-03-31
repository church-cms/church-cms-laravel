#!/usr/bin/env python3
"""Generate all remaining PhpDoc replacements for Church CMS models."""

import re
from pathlib import Path

# Descriptions for each model based on their purpose
model_descriptions = {
    'FaqCategory': ('FAQ category management', 'Organizes frequently asked questions into categories'),
    'Favorite': ('User favorite tracking', 'Tracks user favorites for sermons and other content'),
    'Feedback': ('User feedback management', 'Manages feedback and support tickets from members'),
    'FeedbackMessage': ('Feedback conversation messages', 'Represents individual messages within feedback threads'),
    'Fund': ('Church fund management', 'Manages church funds, donations, and financial tracking'),
    'Gallery': ('Photo gallery management', 'Organizes photo galleries and collections'),
    'GetResponse': ('Email marketing integration', 'Integrates with GetResponse for email campaign tracking'),
    'Group': ('Church groups/ministries', 'Represents church groups, ministries, and communities'),
    'GroupCategory': ('Group categorization', 'Categorizes groups by type or purpose'),
    'GroupLink': ('Group membership tracking', 'Tracks user memberships in groups'),
    'Keyword': ('Chatbot keywords', 'Stores keywords for chatbot trigger recognition'),
    'MailQueue': ('Email sending queue', 'Manages queue of emails to be sent'),
    'MailingList': ('Email mailing lists', 'Represents email distribution lists'),
    'MailinglistSubscriber': ('Mailing list subscriptions', 'Tracks subscriber subscriptions to mailing lists'),
    'Mailtemplate': ('Email templates', 'Stores email message templates for campaigns'),
    'MediaFile': ('Media file management', 'Manages uploaded media files and documents'),
    'NewsLetter': ('Newsletter management', 'Manages church newsletter publications'),
    'Notes': ('Event/entity notes', 'Stores notes attached to events and other entities'),
    'OauthAccessToken': ('OAuth authentication tokens', 'Manages OAuth access tokens for API authentication'),
    'Page': ('Static/dynamic pages', 'Represents static and dynamic website pages'),
    'PageAttachment': ('Page attachments', 'Stores files attached to pages'),
    'PageCategory': ('Page categorization', 'Organizes pages into categories'),
    'PageDetail': ('Page interaction tracking', 'Tracks user interactions (likes, comments) on pages'),
    'Payaccount': ('Payment account configuration', 'Stores payment gateway account credentials'),
    'Paymentgateway': ('Payment gateway setup', 'Configures payment processing gateways'),
    'Permission': ('System permissions', 'Defines permissions for role-based access control'),
    'PermissionUser': ('User permission assignments', 'Assigns permissions directly to users'),
    'Photos': ('Gallery photos', 'Stores individual photos within galleries'),
    'Plan': ('Subscription plans', 'Defines subscription plan tiers and pricing'),
    'PostComment': ('Post comments', 'Represents comments on user posts'),
    'PostCommentDetail': ('Comment interactions', 'Tracks likes/dislikes on post comments'),
    'PostDetail': ('Post interactions', 'Tracks likes/dislikes on posts'),
    'PostTag': ('Post tags', 'Associates tags with posts for categorization'),
    'PrayerRequest': ('Prayer requests', 'Manages prayer requests from the community'),
    'PrayerResponse': ('Prayer responses', 'Tracks responses to prayer requests'),
    'Quote': ('Inspirational quotes', 'Stores motivational quotes for members'),
    'Reminder': ('Event/prayer reminders', 'Manages reminders for events and prayer requests'),
    'Role': ('User roles', 'Defines user roles for the system'),
    'SendMail': ('Sent email tracking', 'Records emails sent through the system'),
    'SermonLink': ('Sermon media links', 'Stores links to sermon videos and audio files'),
    'Setting': ('System settings', 'Stores global system configuration settings'),
    'Smstemplate': ('SMS message templates', 'Stores SMS message templates'),
    'Smtp': ('Email configuration', 'Stores SMTP email server configuration'),
    'State': ('State/province locations', 'Represents states or provinces for geographic hierarchy'),
    'Subscribers': ('Email subscribers', 'Manages email subscribers'),
    'Subscription': ('Church subscriptions', 'Represents subscription plans purchased by churches'),
    'Tag': ('Content tags', 'Stores tags for content categorization'),
    'Usergroup': ('User group roles', 'Defines user group classifications'),
    'VideoConference': ('Video conference sessions', 'Manages video conference meetings and sessions'),
    'VideoConferenceUser': ('Conference participants', 'Tracks users participating in video conferences'),
    'Vote': ('Vote/rating system', 'Stores votes and ratings on content'),
    'Webhook': ('Event webhooks', 'Manages webhook configurations for event notifications'),
    'Widget': ('Dashboard widgets', 'Represents configurable dashboard widgets'),
}

def read_file(path):
    """Read file content."""
    with open(path, 'r', encoding='utf-8') as f:
        return f.read()

def extract_class_name(content):
    """Extract class name from file."""
    match = re.search(r'class\s+(\w+)', content)
    return match.group(1) if match else None

def create_phpdoc(class_name):
    """Create PhpDoc for a model."""
    if class_name in model_descriptions:
        short_desc, long_desc = model_descriptions[class_name]
    else:
        short_desc = f"{class_name} model"
        long_desc = f"{class_name} model in the Church CMS application"

    phpdoc = f"""/**
 * {class_name} Model
 *
 * {long_desc}
 *
 * @package App\\Models
 * @property int $id Primary key
 * @property \\Carbon\\Carbon $created_at Record creation timestamp
 * @property \\Carbon\\Carbon $updated_at Record update timestamp
 */
"""
    return phpdoc

def main():
    models_path = Path("/Users/karthick/Code/church-cms-laravel/app/Models")
    all_files = sorted(models_path.glob("*.php"))

    # Find files needing documentation
    need_phpdoc = []
    for file in all_files:
        content = read_file(file)
        if not content.strip().startswith('<?php\n\nnamespace'):
            continue
        # Check if file starts with PhpDoc
        if "/**" not in content[:200]:
            class_name = extract_class_name(content)
            if class_name:
                need_phpdoc.append((file, class_name, content))

    # Generate replacements
    replacements = []
    for file, class_name, content in need_phpdoc:
        if class_name in ['User', 'Church', 'Post', 'Sermon', 'Userprofile', 'Events', 'Help']:
            # Skip ones we've already done
            continue

        phpdoc = create_phpdoc(class_name)

        # Find the pattern to replace
        # Pattern: last use statement -> class declaration
        match = re.search(r'(^use .*?;)\n\nclass\s+' + class_name, content, re.MULTILINE | re.DOTALL)
        if match:
            old_pattern = match.group(0)
            new_pattern = match.group(1) + '\n\n' + phpdoc + 'class ' + class_name

            replacements.append({
                'file': file.name,
                'class': class_name,
                'old': old_pattern,
                'new': new_pattern
            })

    # Print replacements in JSON format
    import json
    print(json.dumps(replacements, indent=2))
    print(f"\nTotal replacements: {len(replacements)}")

if __name__ == '__main__':
    main()
