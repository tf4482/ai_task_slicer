# Translation Guide

This application now supports multiple languages with German translation implemented alongside English.

## Features

### 1. Language Support
- **English (en)** - Default language
- **German (de)** - Full translation available

### 2. Automatic Language Detection
- Language is automatically detected from browser's Accept-Language header
- Supports English (en) and German (de) based on browser preference
- Language preference is stored in session for consistency
- Falls back to English if browser language is not supported

### 3. Translated Components

#### Application Components
- Todo Resource (forms, tables, filters)
- Todo Overview Widget (all text and notifications)
- Language Switcher Widget
- All user-facing messages and labels

#### Filament Framework Components
- Actions (create, edit, delete, etc.)
- Tables (columns, filters, pagination)
- Forms (field labels, validation messages)
- Panels (navigation, user menu)

#### AI Service Responses
- Subtask generation responses in user's language
- Task improvement suggestions in user's language
- All AI-generated content respects current locale

## Implementation Details

### Translation Files

#### Application Translations
- `lang/en/app.php` - English application translations
- `lang/de/app.php` - German application translations

#### Filament Framework Translations
- `lang/de/filament-actions.php` - German action translations
- `lang/de/filament-forms.php` - German form translations
- `lang/de/filament-panels.php` - German panel translations
- `lang/de/filament-tables.php` - German table translations

### Key Components

#### Browser Language Detection
- **File**: `app/Http/Middleware/SetLocale.php`
- Automatically detects user's preferred language from browser
- Parses Accept-Language header with quality values
- Stores detected language in session for persistence
- Supports language codes like 'de-DE', 'de-CH' → 'de'

#### AI Service Localization
- **File**: `app/Services/OllamaService.php`
- AI responses automatically generated in user's current language
- Subtask generation respects locale (German users get German subtasks)
- Task improvements provided in appropriate language
- Language instruction prepended to all AI prompts
- **Task preservation**: Original task titles are never changed, only enhanced with details

#### Locale Middleware
- **File**: `app/Http/Middleware/SetLocale.php`
- Automatically sets the application locale based on session data
- Applied to all Filament panel requests

#### Configuration
- **File**: `config/app.php`
- Added `available_locales` configuration
- Easily extensible for additional languages

### Usage Examples

#### In PHP Code
```php
// Get translated text
__('app.todos')                    // Returns "Todos" (EN) or "Aufgaben" (DE)
__('app.my_todo_list')            // Returns "My Todo List" (EN) or "Meine Aufgabenliste" (DE)
__('app.add_new_todo')            // Returns "Add New Todo" (EN) or "Neue Aufgabe hinzufügen" (DE)
```

#### In Blade Templates
```blade
{{ __('app.language') }}          <!-- Language switcher label -->
{{ __('app.completed') }}         <!-- Status labels -->
{{ __('app.pending') }}
```

#### In Filament Resources
```php
// Form field labels
Forms\Components\TextInput::make('title')
    ->label(__('app.title'))

// Table column labels
Tables\Columns\TextColumn::make('title')
    ->label(__('app.title'))

// Filter labels
Tables\Filters\TernaryFilter::make('completed')
    ->label(__('app.status'))
    ->placeholder(__('app.all_todos'))
    ->trueLabel(__('app.completed'))
    ->falseLabel(__('app.pending'))
```

## Adding New Languages

To add support for additional languages:

1. **Add to Configuration**
   ```php
   // config/app.php
   'available_locales' => [
       'en' => 'English',
       'de' => 'Deutsch',
       'fr' => 'Français',  // New language
   ],
   ```

2. **Create Translation Files**
   ```
   lang/fr/app.php
   lang/fr/filament-actions.php
   lang/fr/filament-forms.php
   lang/fr/filament-panels.php
   lang/fr/filament-tables.php
   ```

3. **Language Detection**
   The middleware will automatically detect the new language from browser preferences.

## Testing Translations

Use the built-in test command to verify translations:

```bash
php artisan test:translations
```

This command will display sample translations in all supported languages.

## Language Detection Behavior

- **First Visit**: Language is automatically detected from browser's Accept-Language header
- **Subsequent Visits**: Language preference is remembered via session storage
- **Supported Languages**: English (en), German (de)
- **Fallback**: Defaults to English if browser language is not supported
- **Quality Parsing**: Respects browser language quality preferences (q-values)
- **Language Codes**: Supports both full codes (de-DE) and short codes (de)

### Examples of Browser Language Detection

| Browser Language Setting | Detected Locale | Result |
|--------------------------|-----------------|---------|
| German (Germany) | de-DE | German (de) |
| German (Switzerland) | de-CH | German (de) |
| English (US) | en-US | English (en) |
| French (France) | fr-FR | English (en) - fallback |
| German, English | de,en;q=0.9 | German (de) - highest priority |

## File Structure

```
lang/
├── en/
│   └── app.php
├── de/
│   ├── app.php
│   ├── filament-actions.php
│   ├── filament-forms.php
│   ├── filament-panels.php
│   └── filament-tables.php
app/
└── Http/Middleware/
    └── SetLocale.php
```

## Notes

- All user-facing text has been extracted to translation files
- The implementation follows Laravel's localization best practices
- Filament framework components are fully translated
- German translations are complete and ready for use
- Language is automatically detected from browser preferences
- No manual language switching required - works seamlessly based on user's browser settings
- Session persistence ensures consistent language experience across page loads
