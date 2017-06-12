Control structures
==================

A few language constructs are available in HSL in order to control program flow.

.. _echo:

echo
----

The ``echo`` statement will convert the expression to a string and print its value.

::

	echo expression ;

.. code-block:: hsl

	echo "hello world";

.. _if:

if
--

One of the most basic control structures in HSL is the `if` statement, it allows conditional control flow.

::

	if (expression)
		statement

In addition to `if` there is also an `else` branch available; executed if the expression yields false

::

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

The ternary operator is an expression and allows `expression branching`, if the `if_true_expression` is omitted (Elvis operator) then the value of `expression` is used if it tests positive for truthiness, also the expression is not re-evaluated.

::

	expression ? if_true_expression : if_false_expression
	expression ? : if_false_expression

The ternary operator is right-associative with makes them stackable like if-else statements.

.. code-block:: hsl

	$var = isset($arg) ? $arg : "default value";

Null coalescing operator
^^^^^^^^^^^^^^^^^^^^^^^^

The null coalescing operator is an expression and allows `expression branching`, if the `variable` tests positive for :func:`isset` and is not a value of None (null) it is used. The expression which makes up the variable is not re-evaluated.

::

	variable ?? if_false_expression

The null coalescing operator is right-associative with makes them stackable like if-else statements.

.. code-block:: hsl

	$data = json_decode(...);
	$var = $data["settings"] ?? "default value";

foreach
-------

`foreach` loops allows iteration on array values to execute the same statements multiple times.

::

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

`break` will abort the loop iteration of a `foreach`, `while` and `forever` loop and also the execution of `switch` statements.

continue
^^^^^^^^

`continue` will abort the current loop iteration of a `foreach`, `while` and `forever` loop, and restart on the next iteration.

while
-----

`while` statements allows conditional loops.

::

	while ( expression )
		statements

.. code-block:: hsl

	$i = 0;
	while ($i < 10) {
		echo $i;
		$i += 1;
	}

break
^^^^^

`break` will abort the loop iteration of a `foreach`, `while` and `forever` loop and also the execution of `switch` statements.

continue
^^^^^^^^

`continue` will abort the current loop iteration of a `foreach`, `while` and `forever` loop, and restart on the next iteration.

forever
-------

`forever` statements allows indefinite loops.

::

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

`break` will abort the loop iteration of a `foreach`, `while` and `forever` loop and also the execution of `switch` statements.

continue
^^^^^^^^

`continue` will abort the current loop iteration of a `foreach`, `while` and `forever` loop, and restart on the next iteration.

switch
------

`switch` statements are in many ways similar to nested if-else statements. `case` expressions are compared to the `switch` expression until a match is found. If no match is found, and a `default` label exists, it will be executed.

::

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

The `include` statement allows code to be structured in logical modules and shared between different scripts. The include path can be any of the supported `file` path formats (``file:X`` where ``file:`` is implicit). `include`'s file name argument do not support variable interpolation nor expression since the include needs to be resolved at compile time. The statements in the included file are included in-place (replacing the `include` statement itself).

::

	include string ;

.. code-block:: hsl

	include "file:1";
	include "1";

.. note::

	The same file may be included multiple times. However cyclic inclusion is not permitted.

include_once
^^^^^^^^^^^^

The `include_once` keyword will only include the file if it hasn't been included before.

::

	include_once string ;

import
------

The `import` statement allows code to be structured in logical modules and shared between different scripts very much like `include_once` with the difference that all symbols which should be used in the calling script has to be explicitly imported. Also instead of running the imported code directly it is executed in a seperate context (with its own function and variable symbol table) referred to as "the module's global scope". If a file is imported multiple times (regardless of the symbols imported) its code will only be executed once (a behaviour which could be used to initialize global state), very much like `include_once` would behave. All symbols in a module's symbol table is exported (by default), that include symbols which the module itself has imported from another module (a.k.a forwarding imports). An import can not be conditionally and must be defined unconditionally in the script (that usually means at the top of a script).

::

	import { symbol [ as symbol ] [ , ... ] } from string;

.. code-block:: hsl

	import { foo, bar as baz, $x as $y } from "module";
	import { $x as $y } from "module";

.. note::

	The same file may be imported multiple times, but it will only be executed once. However cyclic inclusion is not permitted.

variables
^^^^^^^^^

A variable in the module's global scope may be imported into the global scope. An imported variables is imported by reference (and not by value), hence all changes to the variable in the module will be reflected by the imported variable. An import statement is not allowed to overwrite variables in the local scope (if a conflict occures, it should be imported under another name).

.. code-block:: hsl

	import { $x, $y as $z } from "file:module";

functions
^^^^^^^^^

A function in the module's global scope may be imported into the global scope. An imported function (when executed) is executed in the module's global scope. Hence, the `global` keyword imports from the module's global context.

.. code-block:: hsl

	import { v1, v2, v2 as vLatest } from "file:module";

.. _user_function:

function
--------

It's possible to write new functions in HSL, and also to override builtin :doc:`functions <functions>`. A function may take any number of arguments and return a value using the :ref:`return` statement. If non-variadic arguments are specified, the number of argument given by the caller must match the number of required arguments in the function definition.

::

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

``and`` ``array`` ``as`` ``barrier`` ``break`` ``builtin`` ``cache`` ``case`` ``closure`` ``continue`` ``default`` ``echo`` ``else`` ``false`` ``foreach`` ``forever`` ``from`` ``function`` ``global`` ``if`` ``import`` ``include`` ``include_once`` ``isset`` ``not`` ``none`` ``object`` ``or`` ``return`` ``switch`` ``true`` ``unset`` ``while``

You *should* avoid using keywords available in other general purpose languages and they may be added in the future. That includes keywords such as `for`, `class`, `this`, `private`, `public` etc.

Function scope
**************

Named functions are scoped either in the global scope (if not defined inside another function) or function scoped (a nested scope, may access functions in the previous scope). They are unconditionally registered at compile-time (control flow is not taken into consideration). Hence it doesn't matter where in the scope it's defined (eg. before or after it's being called).

.. code-block:: hsl

	funcname("World");
	function funcname($name) {
		echo "Hello $name";
	}

.. note::
	Named functions are "hoisted".

Anonymous functions
^^^^^^^^^^^^^^^^^^^

The syntax for :ref:`anonymous functions <anonymous_functions>` are the same as for named functions, with the exception that the function name is omitted. Hence they must be called by their value and not by name.

::

	function (argument-list) {
		return expression;
	};

.. code-block:: hsl

	$variable = function ($name) {
		echo "Hello $name";
	};
	$variable("World");

.. note::

	An anonymous function may be used as an `immediately-invoked function expression` (IIFE), meaning it may be invoked directly.

	.. code-block:: hsl

		echo function ($name) {
			return "Hello $name";
		}("World");

.. _closure:

Closure functions
^^^^^^^^^^^^^^^^^

The difference between an anonymous function and a closure function is that a closure function may capture (close over) the environment in which it is created. An anonymous function can be converted to a closure by adding the `closure` keyword followed by a capture list after the function argument list. These variables are captured by reference from the parent scope (function or global) in which they are created.

::

	function (argument-list) closure (variable-list) {
		return expression;
	};

Most languages which implement closures capture (closes over) the entire scope (doesn't use the concept of a capture list). HSL does not with the reasoning that all variables are function local; if the entire scope were to be closed over ambiguities could easily arise, and secondly it allows the developer to explicitly state the intention of the code.

.. code-block:: hsl

	function makeCounter() {
		$n = 0;
		return [
			"inc" => function () closure ($n) { $n += 1; },
			"get" => function () closure ($n) { return $n; },
		];
	}
	$counter1 = makeCounter();
	$counter2 = makeCounter();

	$counter1["inc"]();

	echo $counter1["get"](); // 1
	echo $counter2["get"](); // still 0, $counter2 hasn't been updated

.. note::

	This feature is similar to the PHP implementation of closures (`use`) however HSL's `closure` statement captures by reference.

In order to capture by value, the following `immediately-invoked function expression` (IIFE) pattern may be used.

.. code-block:: hsl

	$i = 3;
	$f = function ($i) { return function () closure ($i) { return $i * $i; }; } ($i);
	$i = 10;
	echo $f(); // 3 * 3 = 9

.. _return:

return
^^^^^^

The `return` statement return a value from a function. If the expression is omitted a value of `none` is returned.

::

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

Formal parameters may be initialized with a default value if not given by the caller. Default values may only defined as trailing parameters in the function definition. Constant expressions which can be evaluated during compile-time may be used as default values (e.g. ``$a = 10 * 1024`` and ``$a = []``).

::

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

Arbitrary-length argument lists are supported using the ``...$argument`` syntax when declaring a function, the rest of the arguments which were not picked up by an other named argument will be added to the last variable as an array. This variable has to be defined at the end of the argument list.

::

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
The `global` statement allows variables to be imported in to a local function scope (by reference). If the variable is not defined at the time of execution (of the global statement) it will simply be marked as "global" and if later assigned; written back to the global scope once the function returns. If the variable that is imported to the function scope already exists in the function scope an error will be raised. If an imported variable is read-only, it will be read-only in the function scope as well.

::

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

Argument unpacking make it possible to call a function with the arguments unpacked from an array at runtime, using the `spread` or `splat` operator (``...``). The calling rules still apply, the argument count must match. This make it easy to override function.

::

	funcname(...expression)
	$variable(...expression)

.. _builtin_keyword:

builtin
*******

The `builtin` statement allows you to explicitly call the builtin version of an overridden function.

::

	builtin funcname()
	builtin funcname

.. code-block:: hsl

	function strlen($str) {
		echo "strlen called with $str";
		return builtin strlen($str);
	}

	echo strlen("hello");

.. _class_statement:

class
-----

The `class` statement can be used to declare new types of classes. The `class-name` must be a valid function name.  In order to create a new instance of a class (object) call the class by name using the function calling convention. Class instances (objects) are not copy on write and all copies reference the same object.

::

	class class-name
	{
		constructor() {}
		function function-name() {}
		static function function-name() {}
		static $variable = initial-value;
	}

constructor
^^^^^^^^^^^

The constructor (function) is a special function declared inside the class statement. This function (if it exist) is called when an object is created, all arguments from the class-name calling is passed to the constructor function. The constructor function supports all features of function calling (such as default arguments). The constructor is usually used to initialize object instance variables on the special ``$this`` variable.

.. code-block:: hsl

	class Foo
	{
		constructor($a, $b) { $this->a = $a; }
	}
	$x = Foo(5);

.. note::

	There is no destructor. Objects are destructed (garbage collected) when they aren't referenced by anyone.

Instance
^^^^^^^^

An instance of a class is created by calling the name of the class (hence calling the constructor). This will create a special ``$this`` variable bound to the object. Property and method access is done with the :ref:`property access <propertyoperator>` operator (``->``) or :ref:`subscript <subscript>` operator (``[]``).

variables
*********

An instance variable is usually created on the ``$this`` object (variable) in the constructor function. At any time, new properites may be added and removed on the ``$this`` object.

.. code-block:: hsl

	class Foo
	{
		constructor() { $this->x = 5; }
	}
	$x = Foo();
	echo $x->x;

functions
*********

A instance function is a function declared in a class statement and is only available on object instances. On execution it has access to the object's ``$this`` variable.

.. code-block:: hsl

	class Foo
	{
		function setX() { $this->x = 5; }
	}
	$x = Foo();
	echo $x->setX();

Static
^^^^^^

A static function or variable is not bound to a class instance instead they are only scoped by the class name using the :ref:`scope resolution <scopeoperator>` operator (``::``). Static members are not available on instance objects.

variables
*********

A static variable is declared within a class statement using the `static` keyword. A static variable is namespaced to the scope of the class name and it's initialized at compile time (but can be updated and used at runtime). A static variable can only be initialized to a constant value (eg. a number or a string).

.. code-block:: hsl

	class Foo
	{
		static $x = 10;
	}
	echo Foo::$x;

functions
*********

A static function is declared within a class statement using the `static` keyword. A static function is namespaced to the scope of the class name. On execution it does not has access to a ``$this`` variable. Instead to hold state, a static function usually use static class variables.

.. code-block:: hsl

	class Foo
	{
		static $x = 10;
		static function getX() { return Foo::$x; }
	}
	echo Foo::getX();

.. _object_keyword:

object
------

The `object` statement can be used to create objects from arrays (these are like anonymous objects, not created from a class). Objects are not copy on write and all copies reference the same object.

::

	object [ "func" => function() { return $this; }, "data" => 123 ]

.. code-block:: hsl

	$x = object [ "get" => function() { return $this["var"]; }];
	$y = $x;
	$y["var"] = 123;
	echo $x->get();

cache
-----

The `cache` statement can be prepended to any named function call. It will cache the function call in a process wide cache. If the same call is done and the result is already in its cache the function will not be executed again, instead the previous result will be used. The cache take the function name and argument values into account when caching.

::

	cache [ cache-option [, cache-option [, ...]]] [builtin] funcname()

The following cache options are available.

   * **ttl** (number) Time to Live (TTL) in seconds for the cache entry if added to the cache during the call. The default time is ``60`` seconds.
   * **ttl_override** (array) An associative array where the key is the `return value` and the value is the overridden `ttl` to be used.
   * **ttl_function** (function) A function taking one argument (the function's `return value`) and returning the `ttl` to be used.
   * **update_function** (function) A function called at cache updates; taking two arguments (the `old` and `new` value) and returning the value to be used and cached.
   * **argv_filter** (array) A list of argument indexes (starting at 1) which should make this cache entry unique. The default is to use all arguments.
   * **force** (boolean) Force a cache-miss. The default is ``false``.
   * **size** (number) The size of the cache (a cache is namespace + function-name). The default is ``32``.
   * **namespace** (string) Custom namespace so that multiple caches can be created per function name. The default is an empty string.
   * **per_message** (boolean) Create a per-message cache (can be used in certain contexts). The default is ``false``.
   * **lru** (boolean) If the cache is full and a cache-miss occur it will remove the Least Recently Used (LRU) entry in order to be able to store the new entry. The default is ``true``.

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

A `barrier` is system-wide `named` mutually exclusive scope, only one execution is allowed to enter the same named scope (applies to all thread and processes). Waiters are queued for execution in random order. Optionally with every barrier comes a shared variable (`shared memory`) which data is shared among executions.

::

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
