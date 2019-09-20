# Cache

Использование:
1. Инициализация: `Cache::init($engine)`.
    #### Варианты $engine:
    - fileCache.php
    - redis
    - memory(массив)

2. Основные методы:
    - `set($key, $item, $ttl = null, $prefix = null): bool` - записать в кэш
    - `get($key, $default): mixed` - взять из кэша
    - `has($key): bool`- проверить наличие ключа
    - `delete($key): bool` - удалить один ключ
    - `clear(): bool` - очистить хранилище полностью

3. Требования:
    - redis (если планируется использовать redis)
    - php 7.1+

