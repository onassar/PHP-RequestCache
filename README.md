PHP Request Cache
===

PHP-RequestCache contains the abstract class **RequestCache**, which acts as a
wrapper for reading, writing, flushing and analyzing data stored throughout the
request.

### Methods
 - **flush**
Clear the request cache
 - **read**
Read data from the cache, determined by the key passed in
 - **write**
Write data to the cache, identified by the key passed in

### Sample Read

    require_once APP . '/vendors/PHP-RequestCache/RequestCache.class.php';
    $key = RequestCache::read('key');
    if (is_null($key)) {
        RequestCache::write('key', 'oliver');
        $key = RequestCache::read('key');
    }
    echo $key;
    exit(0);
