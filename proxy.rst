.. module:: proxy

Proxy
=====

This advanced and powerful script is can be executed before an SMTP command is sent to the internal state machine of the SMTP server (hence before the command is procesed by the SMTP server). It can be used to built custom SMTP commands or modify the current command line before processing.

Variables
---------

These are the read-only arguments available for each command. Depending on when the proxy script is executed, the different objects may contain different information.

========================== ======= ========= ===========
Variable                   Type    Read-only Description
========================== ======= ========= ===========
:ref:`$arguments <v_a8>`   array   yes       Context/hook arguments
:ref:`$connection <v_c8>`  array   yes       Connection/session bound
:ref:`$transaction <v_t8>` array   yes       Transaction bound
$context                   any     no        Connection bound user-defined (default none)
========================== ======= ========= ===========

.. _v_a8:

Arguments
+++++++++

=================== ======= ========================== ===========
Array item          Type    Example                    Description
=================== ======= ========================== ===========
command             string  "XCLIENT ADDR=1.1.1.1"     The SMTP command line issued
=================== ======= ========================== ===========

.. _v_c8:

.. include:: var_connection.rst

.. _v_t8:

.. include:: var_transaction.rst

Functions
---------

.. function:: Pass([options])

  Pass the command to the SMTP server's state machine.

  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **command** (string) Change the SMTP command.
   * **next** (boolean) Request to get the next command as well. The default is ``false``.

.. function:: Reply([reason, [options]])

  Send a reply to the client (The default is code 250). The command is not passed to the SMTP server's state machine.

  :param reason: the message to reply
  :type reason: string or array
  :param array options: an options array
  :return: doesn't return, script is terminated

  The following options are available in the options array.

   * **disconnect** (boolean) Disconnect the client. The default is ``false``.
   * **reply_codes** (array) The array may contain *code* (number) and *enhanced* (array of three numbers). The default is pre-defined.
   * **next** (boolean) Request to get the next command as well. The default is ``false``.

On script error
---------------

On script error :func:`Reply` is called with a generic 421 response.

On implicit termination
-----------------------

If not explicitly terminated then :func:`Pass` is called.
