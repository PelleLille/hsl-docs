Introduction
============

This language reference manual will not cover the basics of programming, instead it's a reference manual for the language.

If you want to test out the language there is a `REPL <http://en.wikipedia.org/wiki/Read-eval-print_loop>`_ command available over SSH and in the web UI. The web UI also features a scripting area to run block code.

Variables
---------

Variables may store values from expression (e.g. constants or function calls). In HSL variables are prefixed with ``$`` followed by ``[a-zA-Z]+[a-zA-Z0-9]*``. Variable names are case-sensitive. Some variables are read-only, hence they are not allowed to be changed. Variables are assigned by value (`copy-on-write <http://en.wikipedia.org/wiki/Copy-on-write>`_).

.. code-block:: hsl

	$var = "foo";
	$bar = $var;
	$bar = "";
	// $var is still "foo"

Functions
---------

A lot of functions are available in the :ref:`function library <funclib>`. Functions are called by named followed by parentheses ``()`` with parameters in between them. Function names are case-sensitive. The argument types must be supported by the function, otherwise an error will be raised.

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

.. _variable:
