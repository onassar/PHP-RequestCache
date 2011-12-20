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
     */
    abstract class RequestCache
    {
        /**
         * _analytics. Cache request/writing statistics array.
         * 
         * @var array
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
         * @var array
         * @access protected
         * @static
         */
        protected static $_store = array();

        /**
         * flush function. Empties request-level cache records.
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
         * getMisses function. Returns the number of request-level missed cache
         *     reads.
         * 
         * @access public
         * @static
         * @return int
         */
        public static function getMisses()
        {
            return self::$_analytics['missed'];
        }

        /**
         * getReads function. Returns the number of request-level successful
         *     cache reads.
         * 
         * @access public
         * @static
         * @return int
         */
        public static function getReads()
        {
            return self::$_analytics['reads'];
        }

        /**
         * getStats function. Returns an associative array of request-level
         *     cache performance statistics.
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
         * getWrites function. Returns the number of successful request-level
         *     cache writes.
         * 
         * @access public
         * @static
         * @return int
         */
        public static function getWrites()
        {
            return self::$_analytics['writes'];
        }

        /**
         * read function. Attempts to read a request-level cache record,
         *     returning null if it couldn't be accessed.
         * 
         * @access public
         * @static
         * @param string $key
         * @return mixed cache record value, or null if it couldn't be retrieved
         */
        public static function read($key)
        {
            // record not found
            if (isset(self::$_store[$key]) === false) {
                ++self::$_analytics['misses'];
                return null;
            }

            // statistic incrementation and value returning
            ++self::$_analytics['reads'];
            return self::$_store[$key];
        }

        /**
         * write function. Writes a value to the request-level cache, based on
         *     the passed in key.
         * 
         * @access public
         * @static
         * @param string $key
         * @param mixed $value
         * @return void
         */
        public static function write($key, $value)
        {
            // null value attempting to be stored
            if ($value === null) {
                throw new Exception(
                    'Cannot perform RequestCache write: attempting to store' .
                    'null value for key *' . ($key) . '*.'
                );
            }

            // statistic incrementation and cache-writing
            ++self::$_analytics['writes'];
            self::$_store[$key] = $value;
        }
    }
