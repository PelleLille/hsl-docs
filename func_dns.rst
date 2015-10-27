:param string query: the query
:param array options: options array
:return: list of items or an extended result
:rtype: array

The following options are available in the options array.

* **timeout** (number) Query timeout in seconds. The default is ``5``.
* **extended_result** (boolean) Get a more extended result. The default is ``false``.
* **servers** (array) List of resolvers. The default is the system wide.

In the ``extended_result`` mode, either ``result`` or ``error`` in set in an associative array. ``dnssec`` is always included. ``result`` is the list of results and ``error`` is the string representation of `rcode` or `h_errno`.
