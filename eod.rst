End-of-DATA
===========

The end-of-DATA (EOD) context is executed on end-of-DATA (the dot ``.``), when the message is fully received (but not yet accepted).
There are two sub-contexts for EOD; one which is executed once per message (command), and other that is executed for each recipient.

.. toctree::

    eodonce
    data
