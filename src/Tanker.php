<?php

namespace BW;

use ArrayAccess;

/**
 * Tanker is an object container with singleton pattern realization
 *
 * @package tanker
 * @author Bocharsky Victor <mail@brainforce.kiev.ua>
 * @version 1.0.0
 */
class Tanker implements ArrayAccess {

	/**
	 * Current version
	 */
	const VERSION = '1.0.0';

    /**
     * The elements container
     * @var array
     */
    protected $container;



    /**
     * Object constructor
     */
    public function __construct() {
        $this->container = array();
        $this->set('tanker', $this);
        $this->set('object_container', $this);
    }



    /* Property Overload Access */
    public function __set($key, $value) {
        return $this->set($key, $value);
    }

    public function __get($key) {
        return $this->get($key);
    }

    /**
     * PHP >= 5.1.0
	 */
    public function __isset($key) {
        return $this->exists($key);
    }

    /**
     * PHP >= 5.1.0
	 */
    public function __unset($key) {
        return $this->remove($key);
    }
    /* /Property Overload Access */

    /* Array Access */
    public function offsetSet($offset, $value) {
        return $this->set($offset, $value);
    }

    public function offsetGet($offset) {
        return $this->get($offset);
    }

    public function offsetExists($offset) {
        return $this->exists($offset);
    }

    public function offsetUnset($offset) {
        return $this->remove($offset);
    }
    /* /Array Access */

    /* Service Method Access */
    public function set($key, $value) {
    	if (isset($this->container[$key])) {
            throw new \RuntimeException(sprintf('Element with key "%s" already exists and can not be overwritten.', $key));
        }

    	$this->container[$key] = $value;

        return $this;
    }
    
    public function get($key) {
    	if ( ! isset($this->container[$key])) {
            throw new \InvalidArgumentException(sprintf('Element with key "%s" is not exists.', $name));
        }

        if ($this->container[$key] instanceof \Closure) {
            $this->container[$key] = $this->container[$key]($this); // converting closures to singleton
        }
         
        return $this->container[$key];
    }

    public function exists($key) {
    	return isset($this->container[$key]);
    }

    public function remove($key) {
    	unset($this->container[$key]);

    	return $this;
    }
    /* /Service Methods Access */

    public function declaredKeys() {
    	return array_keys($this->container);
    }
    
}