<?php

    /**
     * RequestCache
     * 
     * Provides accessors for reading, writing and flushing a request-level
     * cache/data-store.
     * 
     * @author   Oliver Nassar <onassar@gmail.com>
     * @todo     implement key prefixing
     * @abstract
     * @example
     * <code>
     *     // class inclusions
     *     require_once APP . '/vendors/PHP-RequestCache/RequestCache.class.php';
     * 
     *     // attempt to ready key
     *     $key = RequestCache::read('key');
     *     if (is_null($key)) {
     * 
     *         // write value; read
     *         RequestCache::write('key', 'oliver');
     *         $key = RequestCache::read('key');
     *     }
     *     echo $key;
     *     exit(0);
     * </code>
     */
    abstract class RequestCache
    {
        /**
         * _analytics
         * 
         * Cache request/writing statistics array.
         * 
         * @var    array
         * @access protected
         */
        protected static $_analytics = array(
            'misses' => 0,
            'reads' => 0,
            'writes' => 0
        );

        /**
         * _store
         * 
         * @var    array
         * @access protected
         * @static
         */
        protected static $_store = array();

        /**
         * flush
         * 
         * Empties request-level cache records.
         * 
         * @access public
         * @static
         * @return void
         */
        public static function flush()
        {
            self::$_store = array();
        }

        /**
         * getMisses
         * 
         * Returns the number of request-level missed cache reads.
         * 
         * @access public
         * @static
         * @return integer
         */
        public static function getMisses()
        {
            return self::$_analytics['misses'];
        }

        /**
         * getReads
         * 
         * Returns the number of request-level successful cache reads.
         * 
         * @access public
         * @static
         * @return integer
         */
        public static function getReads()
        {
            return self::$_analytics['reads'];
        }

        /**
         * getStats
         * 
         * Returns an associative array of request-level cache performance
         * statistics.
         * 
         * @access public
         * @static
         * @return array
         */
        public static function getStats()
        {
            return self::$_analytics;
        }

        /**
         * getWrites
         * 
         * Returns the number of successful request-level cache writes.
         * 
         * @access public
         * @static
         * @return integer
         */
        public static function getWrites()
        {
            return self::$_analytics['writes'];
        }

        /**
         * read
         * 
         * Attempts to read a request-level cache record, returning null if it
         * couldn't be accessed.
         * 
         * @access public
         * @static
         * @return mixed cache record value, or null if it couldn't be retrieved
         */
        public static function read()
        {
            $keys = func_get_args();
            $reference = &self::$_store;
            foreach ($keys as $key) {
                if (!isset($reference[$key])) {
                    ++self::$_analytics['misses'];
                    return null;
                }
                $reference = &$reference[$key];
            }

            // statistic incrementation and value returning
            ++self::$_analytics['reads'];
            return $reference;
        }

        /**
         * write
         * 
         * Writes a value to the request-level cache, based on the passed in
         * key(s).
         * 
         * @access public
         * @static
         * @return void
         */
        public static function write()
        {
            // presume that the value is the last argument passed in
            $args = func_get_args();
            $keys = &$args;
            $value = array_pop($args);
            $lastKey = array_pop($args);

            // null value attempting to be stored
            if ($value === null) {
                throw new Exception(
                    'Cannot perform RequestCache write: attempting to store' .
                    'null value.'
                );
            }

            // child-keys
            $reference = &self::$_store;
            foreach ($keys as $key) {
                if (!isset($reference[$key])) {
                    $reference[$key] = array();
                }
                $reference = &$reference[$key];
            }

            // statistic incrementation and cache-writing
            ++self::$_analytics['writes'];
            $reference[$lastKey] = $value;
        }
    }
