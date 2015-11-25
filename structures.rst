Control structures
==================

A few language constructs are available in HSL in order to control program flow.

.. _echo:

echo
----

The ``echo`` statement will convert the expression to a string and print its value::

	echo expression ;

.. code-block:: hsl
	
	echo "hello world";

.. _if:

if
--

One of the most basic control structures in HSL is the `if` statement, it allows conditional control flow::

	if (expression)
		statement

In addition to `if` there is also an `else` branch available; executed if the expression yields false::

	if (expression)
		statements
	else
		statements

.. _truthtable:

`if` statements check the expression for truthiness. It does so by checking if the expression is not false; and everything that is not false is by definition true.

+-----------+------------+
| data type | truthiness |
+===========+============+
| number    | != 0       |
+-----------+------------+
| string    | not empty  |
+-----------+------------+
| array     | not empty  |
+-----------+------------+
| none      | never true |
+-----------+------------+

.. code-block:: hsl

	if (true) {
		echo "statement is true";
	}

.. warning::

	If you want to check for truthiness, do not compare values to the :ref:`boolean <boolean>` types `true` or `false` (since they are aliases for numeric values `1` and `0`).

	.. code-block:: hsl

		if (5 == true) // false because (5 == 1) is not true
		if (5) // true because (5 != 0) is true

Ternary operator
^^^^^^^^^^^^^^^^

The ternary operator is an expression and allows `expression branching`, if the `if_true_expression` is omitted the value of `expression` is used if true::

	expression ? if_true_expression : if_false_expression
	expression ? : if_false_expression

The ternary operator is right-associative with makes them stackable like if-else statements.

.. code-block:: hsl

	$var = isset($arg) ? $arg : "default value";

foreach
-------

`foreach` loops allows iteration on array values to execute the same statements multiple times::

	foreach (expression as $val)
		statements

	foreach (expression as $key => $val)
		statements

.. code-block:: hsl

	foreach (["Apple", "Banana", "Orange"] as $fruit) {
		echo $fruit;
	}

.. note::
	
	Use :func:`range` to loop `n` number of times (like in a for-loop).

break
^^^^^

`break` will abort the loop iteration of a `foreach` and `forever` loop and also the execution of `switch` statements.

continue
^^^^^^^^

`continue` will abort the current loop iteration of a `foreach` and `forever` loop, and restart on the next iteration.

forever
-------

`forever` statements allows indefinite loops::

	forever
		statements

.. code-block:: hsl

	$i = 0;
	forever {
		echo $i;
		$i += 1;
	}

break
^^^^^

`break` will abort the loop iteration of a `foreach` and `forever` loop and also the execution of `switch` statements.

continue
^^^^^^^^

`continue` will abort the current loop iteration of a `foreach` and `forever` loop, and restart on the next iteration.

switch
------

`switch` statements are in many ways similar to nested if-else statements. `case` expressions are compared to the `switch` expression until a match is found. If no match is found, and a `default` label exists, it will be executed::

	switch (expression) {
		case expression:
			statements
		break;
		case expression:
			statements
		break;
		default:
			statements
		break;
	}

If executing a statement and `break` is omitted the control flow will fall-through to the next statement.

include
-------

The `include` statement allows code to be structures in logical modules and shared between different scripts. The include path can be any of the supported `file` path formats (``file:X`` or ``file://path.hsl``). `include`'s file name argument do not support variable interpolation nor expression since the include needs to be resolved at compile time. The statements in the included file are included in-place (replacing the `include` statement itself)::

	include string ;

.. code-block:: hsl

	include "file:1";
	include "file://api.hsl";

.. note::
	
	The same file may be included multiple times. However cyclic inclusion is not permitted.

.. _user_function:

function
--------

It's possible to write new functions in HSL, and also to override builtin :doc:`functions <functions>`. A function may take any number of arguments and return a value using the :ref:`return` statement. If non-variadic arguments are specified, the number of argument given by the caller must match the number of required arguments in the function definition::

	function funcname() {
		return expression;
	}
	function funcname($arg1, $arg2) {
		return expression;
	}
	function funcname(...$argv) {
		return expression;
	}

.. warning::
	Recursion is not allowed.

Named functions
^^^^^^^^^^^^^^^

A function may be named (in order to be callable by its name) according to the regular expression pattern :regexp:`[a-zA-Z_]+[a-zA-Z_0-9]*` with the exception of reserved keywords. In order to prevent naming conflicts in the future with added reserved keywords; it may be a good idea to prefix the function name with a unique identifier like ``halon_func``.

``and`` ``array`` ``as`` ``barrier`` ``break`` ``builtin`` ``cache`` ``case`` ``continue`` ``default`` ``echo`` ``else`` ``false`` ``foreach`` ``forever`` ``function`` ``global`` ``if`` ``include`` ``isset`` ``not`` ``or`` ``return`` ``switch`` ``true`` ``unset``

.. note::
	Named functions are unconditionally registered at compile-time (control flow is not taken into consideration). Hence it doesn't matter where in the code it's defined (eg. before or after it's being called).

	.. code-block:: hsl

		funcname();
		function funcname() {
			echo "hello";
		}


Anonymous functions
^^^^^^^^^^^^^^^^^^^

The syntax for :ref:`anonymous functions <anonymous_functions>` are the same as for named functions, with the exception that the function name is omitted. Hence they must be called by their value and not by name::

	function () {
		return expression;
	};

.. code-block:: hsl

	$variable = function () {
		echo "hello";
	};
	$variable();

.. _return:

return
^^^^^^

The `return` statement return a value from a function. If the expression is omitted a value of `none` is returned::

	function funcname() {
		return [ expression ];
	}
	
.. code-block:: hsl

	function funcname() {
		return 42;
	}

.. note::
	If the `return` statement is omitted and execution reached the end of the function, a value of `none` is returned. This is fine if the function is a `void` function.

Default argument
^^^^^^^^^^^^^^^^

Formal parameters may be initialized with a default value if not given by the caller. Default values may only defined as trailing parameters in the function definition. Constant expressions which can be evaluated during compile-time may be used as default values (e.g. ``$a = 10 * 1024`` and ``$a = []``)::
	
	function funcname($arg1 = constant_expressions) {
		statements
	}

.. code-block:: hsl

	function hello($name = "World") {
   		return "Hello $name.";
	}
	echo hello(); // Hello World.
	echo hello("You"); // Hello You.

.. _variadicfunction:

Variadic function
^^^^^^^^^^^^^^^^^

Arbitrary-length argument lists are supported using the ``...$argument`` syntax when declaring a function, the rest of the arguments which were not picked up by an other named argument will be added to the last variable as an array. This variable has to be defined at the end of the argument list::
	
	function funcname($arg1, ...$argN) {
		statements
	}

.. code-block:: hsl

	function avg(...$values) {
		$r = 0;
		foreach ($values as $v)
			$r += $v;
		return $r / count($values);
	}

	$values = [0, 5, 10, 15];
	echo avg(...$values);

.. _global-keyword:

global
^^^^^^
The `global` statement allows variables to be imported in to a local function scope. If the variable is not defined at the time of execution (of the global statement) it will simply be marked as "global" and if later assigned; written back to the global scope once the function returns. If the variable that is imported to the function scope already exists in the function scope an error will be raised. If an imported variable is read-only, it will be read-only in the function scope as well::

	function funcname() {
		global $variable[, $variable [, ...]];
	}

.. code-block:: hsl

	function Deliver() {
		global $recipient;
		echo "Message sent to $recipient";
		builtin Deliver();
	}
	Deliver();

.. _function_calling:

Function calling
^^^^^^^^^^^^^^^^

.. _argumentunpacking:

Argument unpacking
******************

Argument unpacking make it possible to call a function with the arguments unpacked from an array at runtime, using the `spread` or `splat` operator (``...``). The calling rules still apply, the argument count must match. This make it easy to override function::

	funcname(...expression)
	$variable(...expression)

.. _builtin_keyword:

builtin
*******

The `builtin` statement allows you to explicitly call the builtin version of an overridden function::

	builtin funcname()
	builtin funcname

.. code-block:: hsl

	function strlen($str) {
		echo "strlen called with $str";
		return builtin strlen($str);
	}

	echo strlen("hello");

cache
-----

The `cache` statement can be prepended to any named function call. It will cache the function call in a process wide cache. If the same call is done and the result is already in its cache the function will not be executed again, instead the previous result will be used. The cache take the function name and argument values into account when caching.::

	cache [ cache-option [, cache-option [, ...]]] [builtin] funcname()

The following cache options are available.

   * **ttl** (number) Time to Live (TTL) in seconds for the cache entry if added to the cache during the call. The default time is ``60`` seconds.
   * **ttl_override** (array) An associative array where the key is the `return value` and the value is the overridden `ttl` to be used.
   * **ttl_function** (function) A function taking one argument (the function's `return value`) and returning the `ttl` to be used.
   * **update_function** (function) A function called at cache updates; taking two arguments (the `old` and `new` value) and returning the value to be used and cached.
   * **argv_filter** (array) A list of arguments (positions starting at 1) which should make this cache entry unique. The default is to use all arguments.
   * **force** (boolean) Force a cache-miss. The default is ``false``.
   * **size** (number) The size of the cache (a cache is namespace + function-name). The default is ``32``.
   * **namespace** (string) Custom namespace so that multiple caches can be created per function name. The default is an empty string.
   * **per_message** (boolean) Create a per-message cache (can be used in certain contexts). The default is ``false``.
   * **lru** (boolean) If the cache is full and a cache-miss occur it will remove 10% of the Least Recently Used (LRU) entries in order to be able to store new entries. The default is ``true``.

  There are some special namespaces which are reserved. However, they may still be used with caution.

  * **$messageid** This namespace is used to implement the per-message cache.
  * **"file:X"** This namespace may be used to cache functions using files. It's cleared when the file is changed.

  .. code-block:: hsl

  	// cache both the json_decode() and http() request
	function json_decode_and_http(...$args) {
		    return json_decode(http(...$args));
	}
	$list = cache [] json_decode_and_http("http://api.example.com/v1/get/list");

.. warning::

	Not all functions should be cached. If calls cannot be distinguished by their arguments or if they have side-effects (like Deliver), bad things will happen.

	.. code-block:: hsl

		if (cache [] ScanRPD() == 100)  // The same (and incorrect) result will be used for multiple messages
		    cache [] Reject();          // Reject will only happen once...
		Deliver();                      // ...and all other messages will be delivered.

.. note::

	By default (if not distinguish by `namespace`), all cached calls to the same function name share the same cache bucket, consequently the cache statement with the smallest size set the effective max size for that cache. It's recommended to use different `namespaces` for unrelated function calls.

barrier
-------

A `barrier` is system-wide `named` mutually exclusive scope, only one execution is allowed to enter the same named scope (applies to all thread and processes). Waiters are queued for execution in random order. Optionally with every barrier comes a shared variable (`shared memory`) which data is shared among executions::

	barrier statement {
		statements
	}
	barrier statement => variable {
		statements
	}

.. code-block:: hsl

	barrier "counter" => $var {
		$var = isset($var) ? $var : 0;
		echo $var;
		$var += 1;
	}

.. note::

	Storing large data object is much faster if serialized using :func:`json_encode` and :func:`json_decode`.
