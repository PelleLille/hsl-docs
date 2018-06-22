Operators
==========

Operators are used with one or more values (or expressions) which yields another value (so that the operator itself becomes an expression). Expressions can be combinations of operators, functions and variables. Below are the available operator types described. Note that operators are always part of expressions. If no parentheses are used when combining multiple operators within a statement, the expression is resolved from left to right. Therefore, the expression ``2 + 2 . "test"`` will return ``"4test"``. The exception is multiplication, division and modulus, which has higher priority than plus and minus.

Assignment
----------

Assignments will store the value of the expression to the right of the equal sign (`=`) in a variable to the left. An expression consisting of an assignment will have the value that was assigned. This is useful when assigning a variables in e.g. :ref:`if` statements.

  .. code-block:: hsl

		$var = 42;
		if (($random = rand(0, 9)) > 5) echo "$random is larger than 5";

Augmented assignment
^^^^^^^^^^^^^^^^^^^^

Augmented assignment operators are documented in the arithmetic chapter.

  .. code-block:: hsl

  	$var = 5;
	$var = $var + 5; // 10

	$var = 5;
	$var += 5; // 10

.. note::
	
	Assignments which could be written as augmented assignments are automatically optimized as such by the compiler.

.. warning::

  An assignment to "self" (the same variable) on the right side of an augmented assignment yields "undefined" behavior and should not be relied upon.

  .. code-block:: hsl

	$var = 5;
	$var += ($var = 10);

Destructuring assignment
^^^^^^^^^^^^^^^^^^^^^^^^

Destructuring assignment assigns variables with values taken from an `array` value. If the value is not an `array`, all variables will be assigned `none` or its default value.

  .. code-block:: hsl

 		[$a, $b, $c = 3] = [1, 2];
		echo $a; // 1
		echo $b; // 2
		echo $c; // 3 // default value

Keyed index assignments are also supported.

  .. code-block:: hsl

		[
			"result" => $result = [],
			"error" => $error = 0,
			"dnssec" => $dnssec = false
		] = dns("halon.io", ["extended_result" => true]);

It's however not possible to mix keyed with unkeyed assignments.

.. note::

	If there is less variables in the assignment list than array values, the remaining values will be discarded. 

	.. code-block:: hsl

		[$a] = [0, 1, 2];

	If there is more variables in the assignment list than array values, the remaining variables will be assigned the value of `none` or its default value. 

	.. code-block:: hsl

		[$a, $b = 1, $c = 2] = [0];

Slice assignment
^^^^^^^^^^^^^^^^

Slice assignments uses the :ref:`slicing <slicing>` syntax to add, remove or replace items in an `array` by using the slice operator on the left side of an assigment. The slice referred to will be removed while the items on the right side of the assigment operator will be inserted in place right after the removed items.

.. code-block:: hsl

	$var = [1, 2, 7, 5];
	$var[2:3] = [3, 4]; // 1, 2, 3, 4, 5


Arithmetic
----------

These are the arithmetic operators supported, most of which operates on :ref:`numbers <number>`. The `operator associativity <http://en.wikipedia.org/wiki/Operator_associativity>`_ follow the rules of most languages (e.g. C); explicit parentheses may be added to change or clarify the expression.

.. code-block:: hsl

	$var = (3 - 2) + 2;

+---------------+----------+----------------------+--------------------------------+
|               | Operator | Augmented assignment | Augmented assignment expansion |
+===============+==========+======================+================================+
| Addition      | \+       | +=                   | x = x + y                      |
+---------------+----------+----------------------+--------------------------------+
| Increment     | \++      |                      | x++ and ++x                    |
+---------------+----------+----------------------+--------------------------------+
| Subtraction   | \-       | -=                   | x = x - y                      |
+---------------+----------+----------------------+--------------------------------+
| Decrement     | ``--``   |                      | ``x--`` and ``--x``            |
+---------------+----------+----------------------+--------------------------------+
| Multiplication| \*       | \*=                  | x = x \* y                     |
+---------------+----------+----------------------+--------------------------------+
| Division      | /        | /=                   | x = x / y                      |
+---------------+----------+----------------------+--------------------------------+
| Modulus       | %        | %=                   | x = x % y                      |
+---------------+----------+----------------------+--------------------------------+
| Exponentiation| \*\*     | \*\*=                | x = x \*\* y                   |
+---------------+----------+----------------------+--------------------------------+
| Concatenation | .        | .=                   | x = x . y                      |
+---------------+----------+----------------------+--------------------------------+

+------------+---------------+---------------+
| Precedence | Operator      | Associativity |
+============+===============+===============+
|          1 | .             | Left to right |
+------------+---------------+---------------+
|          2 | \+ \-         | Left to right |
+------------+---------------+---------------+
|          3 | \* / %        | Left to right |
+------------+---------------+---------------+
|          4 | \*\*          | Right to left |
+------------+---------------+---------------+

.. note::

	HSL has constant folding, so numeric calculations are done at compile-time. Which means that ``3600 * 24`` is just as fast as using the constant ``86400``.

.. note::

	The ** operator should be used for performance instead of the :func:`pow` function.

String
------

Strings support the :ref:`subscript <subscript>` and :ref:`slicing <slicing>` operator documented in the array section.

Concatenation
^^^^^^^^^^^^^

It's possible to use the ``.`` concatenation operator on any data type (except ``None``), in which case both operands will be casted to a string.

.. code-block:: hsl

	echo "Hello " . "World";
	echo "A number " . 5.5;
	echo 1.0 . 2.5; // "12.5"

Array
-----

.. _subscript:

Subscript
^^^^^^^^^

Single items in arrays can be accessed using the subscript (``[]``) operator. This operator may be used on variables, literals or functions.

If reading and the index doesn't exist, ``None`` is returned.

.. code-block:: hsl

	$var = ["bar", "bar"];
	echo $var[2]; // not found none is returned

If assigning to a variable and the index is not found, the variable is converted to an array and the item is created.

.. code-block:: hsl

	$var = [];
	$var[2] = "baz";
	echo $var[2]; // "baz"

If assigning to a variable and the subscript operator is empty ``[]``, the item will be appended to the array (the variable is converted to an empty array first if needed).

.. code-block:: hsl

	$var = [];
	$var[] = "baz";
	echo $var[0]; // "baz"

Numeric indexes are zero based. If the indexing is sequential (starting from zero) the array allows for direct access (random access) where reads and stores are done in constant O(1) time.

::

	 +---+---+---+---+---+
	 | H | a | l | o | n |
	 +---+---+---+---+---+
	   0   1   2   3   4

It's possible to chain the index operator with the [:] :ref:`slicing <slicing>` operator.

The following key casting rules apply.

	* Strings ("1") containing integers are casted to numbers (1).
	* Numbers (1.10) are casted to integers (1) ignoring the decimal part (x.10). 32bit signed integers are used.
	* All other values are matched as-is.

.. code-block:: hsl

	echo ["1"=>123]; // [1=>123]
	echo [1.9=>123]; // [1=>123]
	echo ["1.9"=>123]; // ["1.9"=>123]

.. note::

	Use the :func:`isset` function to check if a key (index) exists in an array.

.. _slicing:

Slicing
^^^^^^^

Slicing is done using the `[first:last]` operator. The indexes of each side of the : may be omitted, first index default to 0, and last index default to the length of the input, thus [:] will return a copy of the inputs values but the keys will re-indexed (numerically). The first index is inclusive and the last index is exclusive. Negative indexes are supported. If indexes causes out-of-bound, an empty type (array or string) is returned. The slicing operator works the same on arrays and strings. Indexes are counted as if the input was iterated; thus associative arrays have no special meaning.

::

	 +---+---+---+---+---+
	 | H | a | l | o | n |
	 +---+---+---+---+---+
	 0   1   2   3   4   5
	-5  -4  -3  -2  -1

.. code-block:: hsl

	$test = "Halon";
	echo $test[:]; // Halon
	echo $test[1:4]; // alo
	echo $test[-1:]; // n
	echo $test[-3:]; // lon
	echo $test[-5:-2]; // Hal
	echo $test[:2] . $test[2:]; // Halon

Push and pop
^^^^^^^^^^^^

+--------------+------------------------------+--------------------------------+
| Operation    | HSL                          | PHP                            |
+==============+==============================+================================+
| shift        | $array = $array[1:];         | array_shift($array);           |
+--------------+------------------------------+--------------------------------+
| unshift      | $array = ["item"] + $array;  | array_unshift($array, "item"); |
+--------------+------------------------------+--------------------------------+
| pop          | $array = $array[:-1];        | array_pop($array);             |
+--------------+------------------------------+--------------------------------+
| push         | $array = $array + ["item"];  | array_push($array, "item");    |
+--------------+------------------------------+--------------------------------+
| push         | $array = $array + "item";    | array_push($array, "item");    |
+--------------+------------------------------+--------------------------------+
| push         | $array[] = "item";           | array_push($array, "item");    |
+--------------+------------------------------+--------------------------------+

When adding two arrays together, associative keys will be merged (the first array's data will overwritten where keys conflict) and numeric indexes will be incremented (regardless if they conflict or not).

Removing
^^^^^^^^

In order to remove specific value(s) from an array (and if push and pop is not appropriate) use the subtraction (``-``) operator to remove based on value (all value matched will be removed) and :func:`unset` to remove based on a specific key (index) or slice. The subtraction operator supports both single items and arrays (where all values will be removed). The array will not be re-indexed (for that use the slice operator (``$var = $var[:]``).

.. code-block:: hsl

	echo ["foo", 5] - 5; // [0=>"foo"]
	echo ["foo", "foo", 5] - "foo"; // [2=>5]
	echo ["foo", 5] - ["foo", 5]; // []

.. note::

	Use the :func:`unset` function to unset values based on the key (index) or slice.

Logic (boolean)
---------------

Logic operators treats all expressions and variables as either true or false. The :ref:`truthiness <truthtable>` depends on the data type.

+------+----------+--------------+
| Test | Operator | Descriptions |
+======+==========+==============+
| and  | and      | And operator |
+------+----------+--------------+
| or   | or       | Or operator  |
+------+----------+--------------+
| not  | not      | Not operator |
+------+----------+--------------+
| not  | !        | Not operator |
+------+----------+--------------+

Short-circuit evaluation
^^^^^^^^^^^^^^^^^^^^^^^^

The ``and`` and ``or`` operations are short-circuit. They will only evaluate the right statement if the left one doesn't `satisfy <http://en.wikipedia.org/wiki/Truth_table>`_ the condition. In the example below, ``bar()`` is not executed because ``foo()`` return `true`, thus satisfying the condition.

.. code-block:: hsl

	function foo() { return true; }
	function bar() { return false; }

	if (foo() or bar()) echo "foo or bar";

Bitwise
-------

Bitwise operators treat their operands as 32 bits signed integers in `two's complement <https://en.wikipedia.org/wiki/Two's_complement>`_ format. The result of these operators are regular :ref:`numbers <number>`.

+------+----------+--------------------------------+
| Test | Operator | Descriptions                   |
+======+==========+================================+
| and  | &        | Bitwise AND operator           |
+------+----------+--------------------------------+
| or   | \|       | Bitwise OR operator            |
+------+----------+--------------------------------+
| xor  | ^        | Bitwise XOR operator           |
+------+----------+--------------------------------+
| not  | ~        | Bitwise NOT operator           |
+------+----------+--------------------------------+
| <<   | <<       | Shift left, padded with zeros  |
+------+----------+--------------------------------+
| >>   | >>       | Shift right, sign-propagating  |
+------+----------+--------------------------------+

.. code-block:: hsl

	$flags = 5;

	$flagA = 0b0001;
	$flagB = 0b0010;
	$flagC = 0b0100;
	$flagD = 0b1000;
	if ($flags & ($flagB | $flagC)) echo "match";

Comparison
----------

These operators compare the expressions (operands) on both sides of the operator with one another, and the expression return either true or false if they matched.

+-------------------------------+----+--------------------------------------------------+----------------+
| Test                          |    | Description                                      | Works on types |
+===============================+====+==================================================+================+
| equality                      | == | Matches for equality                             | Any            |
+-------------------------------+----+--------------------------------------------------+----------------+
| inequality                    | != | Matches for inequality                           | Any            |
+-------------------------------+----+--------------------------------------------------+----------------+
| less than                     | <  | Matches for less than                            | Numbers        |
+-------------------------------+----+--------------------------------------------------+----------------+
| greater than                  | >  | Matches for greater than                         | Numbers        |
+-------------------------------+----+--------------------------------------------------+----------------+
| less or equal than            | <= | Matches for less than                            | Numbers        |
+-------------------------------+----+--------------------------------------------------+----------------+
| greater or equal than         | >= | Matches for greater than                         | Numbers        |
+-------------------------------+----+--------------------------------------------------+----------------+
| regular expression            | =~ | Matches for equality using regular expressions   | Strings        |
+-------------------------------+----+--------------------------------------------------+----------------+
| inequality regular expression | !~ | Matches for inequality using regular expressions | Strings        |
+-------------------------------+----+--------------------------------------------------+----------------+

.. note::

	If comparing two operands of different data type, the result may be "unexpected", therefore always explicitly convert them if needed using functions like :func:`number` and :func:`string`.

.. _regex:

Regular expression
^^^^^^^^^^^^^^^^^^

The regular expression operator (``=~`` and not-match ``!~`` operator) matches a string by default using partial matching. That means it allows a substring to match. To explicit mark the beginning or end of a pattern, use ``^`` for beginning and ``$`` for the end. The regular expression implementation is "Perl Compatible" (hence the function names `pcre_...`), for syntax see the `perlre <http://perldoc.perl.org/perlre.html>`_ documentation. The following :ref:`modifiers<patternmodifiers>` are supported.

.. code-block:: hsl
	
	if ($var =~ ''\bhalon\b'') echo "contain the word halon";

.. note::

	If using :ref:`raw strings <rawstring>` with regular expressions there is no need to escape some characters twice. Literal strings (both :ref:`double-quoted <doublequoted>` (without variable interpolation) and :ref:`raw strings <rawstring>`) as regular expressions will be precompiled for greater performance.

.. seealso::

	For data extraction using regular expressions see :func:`pcre_match` family of functions.

.. _patternmodifiers:

Pattern modifiers
#################

Use pattern modifiers to change the behavior of the pattern engine, they have the capability to make the match case-insensitive and activate UTF-8 support (where one UTF-8 characters may be matched using only one dot) etc. They are activated by encapsulate the pattern using the `/regular_expression/modifiers` syntax. The `regular_expression` part should be a `regular expression`, and the modifiers should be zero or many of.

+----------+-----------------+---------------------------------------------------------------------------------+
| Modifier | Internal define | Description                                                                     |
+==========+=================+=================================================================================+
| i        | PCRE_CASELESS   | Do case-insensitive matching                                                    |
+----------+-----------------+---------------------------------------------------------------------------------+
| m        | PCRE_MULTILINE  | See `perl <http://perldoc.perl.org/perlre.html#Modifiers>`_ documentation       |
+----------+-----------------+---------------------------------------------------------------------------------+
| u        | PCRE_UTF8       | Enable UTF-8 support                                                            |
+----------+-----------------+---------------------------------------------------------------------------------+
| s        | PCRE_DOTALL     | See `perl <http://perldoc.perl.org/perlre.html#Modifiers>`_ documentation       |
+----------+-----------------+---------------------------------------------------------------------------------+
| x        | PCRE_EXTENDED   | See `perl <http://perldoc.perl.org/perlre.html#Modifiers>`_ documentation       |
+----------+-----------------+---------------------------------------------------------------------------------+
| U        | PCRE_UNGREEDY   | See `perl <http://perldoc.perl.org/perlre.html#Modifiers>`_ documentation       |
+----------+-----------------+---------------------------------------------------------------------------------+
| X        | PCRE_EXTRA      | See `perl <http://perldoc.perl.org/perlre.html#Modifiers>`_ documentation       |
+----------+-----------------+---------------------------------------------------------------------------------+

.. note::

	It's not necessary to encapsulate regular expressions with ``//`` unless modifiers are used.

Function
--------

.. _callable:

Call
^^^^

Functions may be :ref:`called <function_calling>` using the ``()`` operator. It applies to both regular functions as well as anonymous functions and named function pointers.

.. code-block:: hsl

	$multiply = function ($x, $y) { return $x * $y };
	echo $multiply(3, 5); // 5

Class
-----

.. _propertyoperator:

Property
^^^^^^^^

The property operator (``->``) may be used to access (non-static) variables and functions on objects (class instances). It acts the same as the :ref:`subscript <subscript>` operator (``[]``).

.. code-block:: hsl

	class makeCounter
	{
		constructor() { $this->n = 0; }
		function inc() { $this->n += 1; }
		function get() { return $this->n; }
	}
	$counter1 = makeCounter();

	$counter1->inc();   // 1
	$counter1["inc"](); // 2
	echo $counter1->get(); // prints 2

.. _scopeoperator:

Scope resolution
^^^^^^^^^^^^^^^^

The scope resolution operator (``::``) is used to access static variables and functions on :ref:`classes <class_statement>`.

::

	class-name :: function
	class-name :: $variable

.. code-block:: hsl

	class MyClass
	{
		static $x = 5;
		static function getX() { return MyClass::$x; }
	}
	echo MyClass::$x; // 5
	echo MyClass::getX(); // 5

Static
######

The scope resolution operator can use the ``static`` keyword in the same class as a shorthand for the class name itself.

.. code-block:: hsl

	class MyClass
	{
		static $x = 5;
		static function getX() { return static::$x; }
	}
