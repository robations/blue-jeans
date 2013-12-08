<?php

namespace Contrebis\BlueJeans;


/**
 *
 */
class ListCollection implements \ArrayAccess, \Countable, \Iterator
{
    /**
     * An array containing the entries of this collection.
     *
     * @var array
     */
    private $_elements;

    /**
     * Initializes a new ArrayCollection.
     *
     * @param array $elements
     */
    public function __construct(array $elements = array())
    {
        $this->_elements = $elements;
    }

    public function toArray()
    {
        return $this->_elements;
    }

    public function first()
    {
        return reset($this->_elements);
    }

    public function last()
    {
        return end($this->_elements);
    }

    public function key()
    {
        return key($this->_elements);
    }

    public function next()
    {
        return next($this->_elements);
    }

    public function current()
    {
        return current($this->_elements);
    }

    public function remove($key)
    {
        if (array_key_exists($key, $this->_elements)) {
            $copy = $this->_elements;
            unset($copy[$key]);
            $copy = array_values($copy);

            return new ListCollection($copy);
        }

        return $this;
    }

    public function removeElement($element)
    {
        $offset = array_search($element, $this->_elements, true);

        if ($offset !== false) {
            $copy = $this->_elements;
            unset($copy[$offset]);
            $copy = array_values($copy);

            return new ListCollection($copy);
        }

        return $this;
    }

    public function offsetExists($offset)
    {
        return $this->containsKey($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        if (!isset($offset)) {
            return $this->add($value);
        }
        return $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        unset($this->_elements[$offset]);
        $this->_elements = array_values($this->_elements);
    }

    public function containsKey($key)
    {
        return isset($this->_elements[$key]) || array_key_exists($key, $this->_elements);
    }

    public function contains($element)
    {
        return in_array($element, $this->_elements, true);
    }

    public function exists(callable $p)
    {
        foreach ($this->_elements as $element) {
            if ($p($element)) {
                return true;
            }
        }
        return false;
    }

    public function indexOf($element)
    {
        return array_search($element, $this->_elements, true);
    }

    public function get($offset)
    {
        if (isset($this->_elements[$offset])) {
            return $this->_elements[$offset];
        }
        return null;
    }

    public function getValues()
    {
        return array_values($this->_elements);
    }

    public function count()
    {
        return count($this->_elements);
    }

    public function set($offset, $value)
    {
        if (!$this->containsKey($offset)) {
            throw new \InvalidArgumentException("Key doesn't exist");
        }
        $this->_elements[$offset] = $value;
    }

    public function add($value)
    {
        $this->_elements[] = $value;
        return true;
    }

    /**
     * @param int $offset Zero-based offset
     * @param mixed $value
     */
    public function insert($offset, $value)
    {
        $this->_elements = array_merge(
            $this->slice(0, $offset)->getValues(),
            array($value),
            $this->slice($offset, $this->count() - $offset)->getValues()
        );
    }

    public function isEmpty()
    {
        return !$this->_elements;
    }

    public function map(callable $func)
    {
        return new static(array_map($func, $this->_elements));
    }

    public function filter(callable $p)
    {
        return new static(array_filter($this->_elements, $p));
    }

    /**
     * @param callable $func A function that takes the initial value and element, and returns the cumulative result.
     * @param mixed $initial
     * @return mixed Of same type as $initial
     */
    public function reduce(callable $func, $initial = null)
    {
        return array_reduce($this->_elements, $func, $initial);
    }

    public function forAll(callable $p)
    {
        foreach ($this->_elements as $element) {
            if (!$p($element)) {
                return false;
            }
        }

        return true;
    }

    public function partition(callable $p)
    {
        $coll1 = $coll2 = array();
        foreach ($this->_elements as $element) {
            if ($p($element)) {
                $coll1[] = $element;
            } else {
                $coll2[] = $element;
            }
        }
        return array(new static($coll1), new static($coll2));
    }

    /**
     * @param callable $cmp
     * @return ListCollection
     */
    public function sort(callable $cmp)
    {
        $arr = array_values($this->_elements);
        usort($arr, $cmp);

        return new static($arr);
    }

    /**
     * Returns a string representation of this object.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('[%s]', implode(', ', $this->_elements));
    }

    public function clear()
    {
        return new ListCollection();
    }

    /**
     * @param $offset
     * @param null $length
     * @return ListCollection
     */
    public function slice($offset, $length = null)
    {
        return new static(array_slice($this->_elements, $offset, $length));
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return $this->offsetExists(key($this->_elements));
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        reset($this->_elements);
    }
}
