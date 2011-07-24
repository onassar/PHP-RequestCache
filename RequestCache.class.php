<?php

    /**
     * Abstract RequestCache class. Provides accessors for reading, writing and
     *     flushing a request-level cache/data-store.
     * 
     * @todo implement prefixing
     * @note doesn't need to take false-value storage into consideration as
     *     APCCache does, since false value can be stored in RequestCache
     *     data-store natively
     * @abstract
     */
    abstract class RequestCache
    {
        /**
         * _misses. Number of failed request-level cache reads/hits.
         * 
         * (default value: 0)
         * 
         * @var int
         * @access protected
         * @static
         */
        protected static $_misses = 0;

        /**
         * _reads. Number of successful request-level cache reads/hits.
         * 
         * (default value: 0)
         * 
         * @var int
         * @access protected
         * @static
         */
        protected static $_reads = 0;

        /**
         * _writes. Number of request-level cache writes/sets.
         * 
         * (default value: 0)
         * 
         * @var int
         * @access protected
         * @static
         */
        protected static $_writes = 0;

        /**
         * flush function. Empties request-level cache records.
         * 
         * @access public
         * @static
         * @return void
         */
        public static function flush()
        {
            unset($GLOBALS['cache']);
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
            return self::$_misses;
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
            return self::$_reads;
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
            return array(
                'misses' => self::$_misses,
                'reads' => self::$_reads,
                'writes' => self::$_writes
            );
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
            return self::$_writes;
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
            if (isset($GLOBALS['cache'][$key]) === false) {
                ++self::$_misses;
                return null;
            }

            // statistic incrementation and value returning
            ++self::$_reads;
            return $GLOBALS['cache'][$key];
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
            // namespace-setup
            if (isset($GLOBALS['cache']) === false) {
                $GLOBALS['cache'] = array();
            }

            // null value attempting to be stored
            if ($value === null) {
                throw new Exception(
                    'Cannot perform RequestCache write: attempting to store' .
                    'null value for key *' . ($key) . '*.'
                );
            }

            // statistic incrementation and cache-writing
            ++self::$_writes;
            $GLOBALS['cache'][$key] = $value;
        }
    }

?>
