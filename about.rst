About
=====

Halon's scripting language is a domain-specific language (`DSL <http://en.wikipedia.org/wiki/Domain-specific_language>`_) for Halon's `email platform <http://halon.io/>`_. It's used to configure the behavior of  processing in a scriptable fashion.

At multiple stages in the email delivery process there are opportunities to affect the behavior (e.g. accept or reject an email message). These are referred to as `flows`, `scripts` or `contexts`, each of which extends the language with additional functions and pre-defined variables.

The syntax is inspired by languages such as `PHP <http://php.net/>`_ (e.g. ``$variable``) and `Python <http://python.org>`_ (e.g. slices ``[0:10]``). It's however **not** based upon any of these, so in some cases even if the syntax or function name is the same, there may be differences in the behavior hence always refer to this documentation.

Design
--------------
Here are some of the internals of Halon Scripting Language (HSL).

Compiled language

	HSL code is compiled into a binary representation. This binary representation is reused between executions.

Constant folding

	As a step in the compilation progress, different optimizations are applied. Constant folding is one of them where constant mathematical expressions are simplified (evaluated) at compile time.

Code pattern optimizations

	HSL tries to detect certain code pattern and replace them with faster alternatives. Eg. `while (true)` is compiled to the equivalent of `forever`.

Dead code elimination

	HSL tries to detect and remove dead code in the compilation stage.

Parallel execution

	HSL code may be executed in parallel. Memory may be synchronized using barriers.

Copy-on-write

	HSL features copy-on-write memory, for the data types array and string. Constants' memory are shared among multiple executions, vastly reducing memory consumption.

R-value optimizations

	R-value expressions are optimized in a folding-style, so that unnecessary memory allocations are avoided.

Garbage collection

	HSL doesn't require manual memory management as it features reference counted garbage collection.

Type system

	HSL is a "dynamically-typed programming language". Featuring boolean, numbers, strings, associative arrays, functions and objects.

Modules

	HSL allows you to structure you code into reusable modules.

Classes

	HSL supports classes to encapsulate and structure data.

Scoping

	Variables are function scoped, in addition to the global scope. Functions can be either globally scoped or function scoped (however they are always registered at compile time; hence they cannot be conditionally defined). Variable scoping may be changed with the `global` keyword.
