# GlitchTip / Sentry Integration Guide

## Описание

GlitchTip интегрирован в проект для централизованного логирования и отслеживания ошибок. Используется пакет `sentry/sentry-laravel`, который совместим с протоколом Sentry и поддерживает GlitchTip.

## Установка и Конфигурация

### 1. Переменные Окружения

Добавьте следующие переменные в файл `.env`:

```env
# GlitchTip / Sentry Configuration
SENTRY_LARAVEL_DSN=https://<key>@glitchtip.example.com/projects/<id>/
SENTRY_ENVIRONMENT=production
SENTRY_TRACES_SAMPLE_RATE=1.0
SENTRY_PROFILES_SAMPLE_RATE=1.0
```

**Параметры:**
- `SENTRY_LARAVEL_DSN` - Ваш DSN (Data Source Name) из GlitchTip
- `SENTRY_ENVIRONMENT` - Окружение (local, staging, production)
- `SENTRY_TRACES_SAMPLE_RATE` - Доля отслеживаемых транзакций (0.0 - 1.0)
- `SENTRY_PROFILES_SAMPLE_RATE` - Доля профилируемых операций (0.0 - 1.0)

### 2. Основные Файлы

- **[config/sentry.php](config/sentry.php)** - Конфигурация Sentry
- **[config/logging.php](config/logging.php)** - Конфигурация логирования с каналом GlitchTip
- **.env** - Переменные окружения

### 3. Использование

#### Логирование ошибок

Ошибки автоматически отправляются в GlitchTip благодаря интеграции с Laravel:

```php
try {
    // ваш код
} catch (Exception $e) {
    \Log::error('Error occurred', ['exception' => $e]);
    // Будет автоматически отправлено в GlitchTip
}
```

#### Отправка сообщений

```php
use Illuminate\Support\Facades\Log;

Log::info('Информационное сообщение');
Log::warning('Предупреждение');
Log::error('Ошибка');
Log::debug('Debug информация');
```

#### Работа с контекстом

```php
Log::withContext([
    'user_id' => auth()->id(),
    'ip_address' => request()->ip(),
])->error('Произошла ошибка');
```

### 4. Breadcrumbs (Хлебные крошки)

Sentry автоматически отслеживает:
- SQL запросы
- HTTP запросы
- Логи приложения
- Кэш операции
- Redis операции
- Очередь задач

Все это отправляется вместе с ошибкой для лучшего контекста.

### 5. GitHub Actions Integration

Для использования GlitchTip в CI/CD процессе, добавьте в `.github/workflows/laravel.yml`:

```yaml
jobs:
  test:
    steps:
      - name: Run tests
        run: php artisan test
        env:
          SENTRY_LARAVEL_DSN: ${{ secrets.SENTRY_LARAVEL_DSN }}
```

### 6. Локальная разработка

Для локальной разработки оставьте `SENTRY_LARAVEL_DSN` пустым:

```env
SENTRY_LARAVEL_DSN=
SENTRY_ENVIRONMENT=local
```

Логи будут писаться локально, но не будут отправляться в GlitchTip.

### 7. Health Endpoint

Проверка работоспособности логирования:

```php
Route::get('/health/logs', function () {
    \Log::info('Health check log');
    return response()->json(['status' => 'ok']);
});
```

## Мониторинг

### Собираемые метрики

- Необработанные исключения
- Логируемые ошибки уровня ERROR и CRITICAL
- Производительность запросов
- Использование БД
- Ошибки кэша и Redis

### Рекомендации для Production

1. Установите `SENTRY_TRACES_SAMPLE_RATE` в 0.1 (10%) для экономии квоты
2. Используйте отдельные проекты для разных окружений
3. Настройте оповещения в GlitchTip UI
4. Регулярно просматривайте и анализируйте ошибки
5. Используйте `release` версию для отслеживания изменений:

```env
APP_VERSION=1.0.0
```

## Ссылки

- [GlitchTip Documentation](https://glitchtip.com/)
- [Sentry Laravel Integration](https://docs.sentry.io/platforms/php/guides/laravel/)
- [Monolog Handlers](https://sentry.io/for/php/)
