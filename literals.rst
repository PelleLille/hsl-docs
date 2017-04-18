Data types and literals
=======================

HSL has multiple data types; `strings`, `numbers`, `arrays` (which also works as an ordered map to store key-value pairs, similar to PHP's `array <http://php.net/manual/en/language.types.array.php>`_) and `functions` (both anonymous functions and named function pointers). These data types may be represented as literals. There is also a `none` (or `null`) data type that is rarely encountered (e.g. a :ref:`return` statement without a value or a failed :func:`json_decode` both of which return `none`).

.. _string:

String
-------

There are two kinds of string literals, `double-quoted strings` and `raw strings`. Double-quoted strings support language features such as variable interpolation and escape sequences. Most functions (e.g. :func:`strlen` and :func:`substr`) are **not** `UTF-8 <http://en.wikipedia.org/wiki/UTF-8>`_ aware, with the exception of :ref:`regular expression <regex>` matching (e.g. :func:`pcre_match`) which may be configured to be UTF-8 aware with the :ref:`/u <patternmodifiers>` modifier.

.. _doublequoted:

Double-quoted string
^^^^^^^^^^^^^^^^^^^^

Variable interpolation replaces ``$variable`` placeholders within string literals. Variables are matched in strings with the following pattern :regexp:`$[a-zA-Z]+[a-zA-Z0-9]`. If needed there is also a more explicit syntax ``${variable}`` (which allows variables mid-words). Interpolating an undeclared variable raises a runtime error.

.. code-block:: hsl

	"$variable"
	"${variable}abc"

+-----------------+---------------------------------+
| Escape sequence | Meaning                         |
+=================+=================================+
| ``\\``          | Backslash (``\``)               |
+-----------------+---------------------------------+
| ``\"``          | Double quote (``"``)            |
+-----------------+---------------------------------+
| ``\$``          | Dollar sign (``$``)             |
+-----------------+---------------------------------+
| ``\n``          | ASCII Linefeed (LF)             |
+-----------------+---------------------------------+
| ``\r``          | ASCII Carriage Return (CR)      |
+-----------------+---------------------------------+
| ``\t``          | ASCII Horizontal Tab (TAB)      |
+-----------------+---------------------------------+
| ``\xhh``        | Character with hex value *hh*   |
+-----------------+---------------------------------+

.. _rawstring:

Raw string
^^^^^^^^^^

Raw strings do not support variable interpolation nor escape sequences. This make them suitable for :ref:`regular expressions <regex>`. Raw strings start and ends with **two single quotes** on each side ``''``, with an optional delimiter in between. The delimiter can be any of ``[\x21-\x26\x28-\x7e]*``; simply put any word.

.. code-block:: hsl

	''raw string''
	'DELIMITER'raw string'DELIMITER'
	'#'raw string'#'

.. note::
	
	There is no performance difference between double-quoted and raw strings containing the same value. However if special characters needs to be escaped then raw string are recommended for clarity.

.. _number:

Number
-------

The number type is a double-precision 64-bit `IEEE 754 <http://en.wikipedia.org/wiki/Double-precision_floating-point_format>`_ value. If converted to a string it will be presented in the most accurate way possible without trailing decimal zeros.

.. code-block:: hsl

	echo 1.0; // 1

.. warning::

	After some arithmetic operations on floating point numbers; the equality (`==`) of two floating point numbers may not be true even if they mathematically "should". This caveat is not unique to HSL, instead it is the result of how computers calculates and stores `floating point numbers <http://en.wikipedia.org/wiki/Floating_point>`_. Arithmetic operations on `numbers` without decimals are not affected.

.. _hexadecimal:

Hexadecimal
^^^^^^^^^^^
Numbers may be entrered as in hexadecimal form (also known as base 16) using the ``0x`` prefix; followed by ``[0-9a-f]+``.

.. code-block:: hsl

	echo 0xfa; // 250

.. _octal:

Octal
^^^^^
Numbers may be entrered in octal form (also known as base 8) using the ``0o`` prefix; followed by ``[0-7]+``.

.. code-block:: hsl

	echo 0o372; // 250

.. _binary:

Binary
^^^^^^
Numbers may be entrered in binary form (also known as base 2) using the ``0b`` prefix; followed by ``[0-1]+``.

.. code-block:: hsl

	echo 0b11111010; // 250

.. _boolean:

Boolean
^^^^^^^
The keywords ``true`` and ``false`` are aliases for `1` and `0`.

.. warning::
	Boolean ``true`` and ``false`` should not be used to test for :ref:`truthiness <truthtable>` e.g. in :ref:`if` statements. :ref:`if` statements checks for values which are `not false`, which isn't the same as numeric 1 (``true``).

	.. code-block:: hsl

		if (5 == true) { } // false: 5 is not equal to 1
		if (5) { } // true: 5 is not false, hence true

.. _arraytype:

Array
------

An array is a very useful container; it can act as an indexed array (automatically indexed at zero, or the highest current index + 1) or as an ordered map (associative array) with any and mixed data types as key and value. The short array syntax for literal arrays ``[]`` is recommended.

.. code-block:: hsl

	// indexed arrays
	echo array("value", "value2");
	echo ["value", "value2"];
	echo [0 => "value", 1 => "value2"];

	// associative arrays
	echo array("key" => "value");
	echo ["key" => "value"];

	// multidimensional arrays
	echo ["key" => ["key" => "value"]];

	// automatic indexing
	echo ["foo", 3=>"bar", "baz"]; // 0=>foo, 3=>bar, 4=>baz

.. note::

	Accessing any element in a zero indexed array using the `subscript` or `slice` operator is very fast (it has the complexity of `O(1)`).

.. _none:

Function
--------

Both `anonymous functions` (closures) and `named function pointers` (references to functions) are available. This datatype is primarly used to be passed as callbacks to other functions.

.. _anonymous_functions:

Anonymous functions
^^^^^^^^^^^^^^^^^^^

An anonymous function is a unnamed :ref:`function <user_function>`, it can be passed as value to a function or assigned to a variable. An anonymous function can also act as a :ref:`closure <closure>`. The :ref:`global-keyword` variable scoping rules apply.

.. code-block:: hsl

	$multiply = function ($x, $y) { return $x * $y };
	echo $multiply(3, 5); // 15

Named function pointers
^^^^^^^^^^^^^^^^^^^^^^^

A named function pointer is a reference to a named function. It can reference both a :doc:`builtin function <functions>` or a :ref:`user-defined function <user_function>`. Prepending the function name with the :ref:`builtin_keyword` keyword works as expected.

.. code-block:: hsl

	function strlen($str) { return 42; }

	$function = strlen;
	echo $function("Hello"); // 42

	$function = builtin strlen;
	echo $function("Hello"); // 5

None
----

This data type is represeneted by the keyword ``none``. It may be used to indicate error-result or no return value from functions such as. :func:`json_decode` (in case of a decode error) or from a user-defined :ref:`user_function` with no or an empty :ref:`return` statement. This data type should **not** be used as an argument to other built-in functions as it yields **undefined** behavior for the most part. The only functions safe to handle this data type is:

 * :func:`is_array`
 * :func:`is_function`
 * :func:`is_number`
 * :func:`is_string`

.. code-block:: hsl
	
	$obj = json_decode("...");
	if ($obj == none)
		echo "None";
	}
