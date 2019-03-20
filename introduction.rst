Introduction
============

This language reference manual will not cover the basics of programming, instead it's a reference manual for the language.

If you want to test out the language there is a `REPL <http://en.wikipedia.org/wiki/Read-eval-print_loop>`_ command available over SSH and in the web UI. The web UI also features a scripting area to run block code.

Variables
---------

Variables may store values from expression (e.g. constants or function calls) or callable function objects such as anonymous functions and named function pointers. In HSL variables are prefixed with ``$`` followed by :regexp:`[a-zA-Z_]+[a-zA-Z0-9_]*`. Variable names are case-sensitive. Some variables are read-only, hence they are not allowed to be assigned to (primarily pre-defined variables in contexts). Variables are assigned by value (`copy-on-write <http://en.wikipedia.org/wiki/Copy-on-write>`_).

.. code-block:: hsl

	$var = "foo";
	$bar = $var;
	$bar = "";
	// $var is still "foo"

.. note::

	Variables in HSL are main/function scoped. However a variable needs to be created in all code paths before being used. :func:`isset` may test if a variable exists.

Functions
---------

A lot of builtin functions are available in the :doc:`function library <functions>`. Functions are called by named followed by parentheses ``()`` with input parameters in between them. Function names are case-sensitive. The argument types must be supported by the function, otherwise an error will be raised. It's also possible to create :ref:`user-defined functions <user_function>`.

.. code-block:: hsl

	echo uptime();
	echo strlen("Hello");

.. warning::
	Calling a function with too few or too many arguments will raise an error, either during compilation time or at runtime (using :ref:`argument unpacking<argumentunpacking>`).

.. _comment:

Comments
--------

Comments may be added to the code using two syntaxes, C-style "multi-line" comments and C++ "single-line" comments.

.. code-block:: hsl

	/*
	   multi-line comment
	*/

	// single-line comment
