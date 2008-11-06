<?php

class MissingSchemaInfoTableException extends Exception {
	public function __construct ( $msg = '', $code = 0 ) {
      parent::__construct ( $msg, $code );
  }
}
class MissingMigrationDirException extends Exception {
	public function __construct ( $msg = '', $code = 0 ) {
      parent::__construct ( $msg, $code );
  }
}
class MissingTableException extends Exception {
	public function __construct ( $msg = '', $code = 0 ) {
      parent::__construct ( $msg, $code );
  }
}
class MissingAdapterException extends Exception {
	public function __construct ( $msg = '', $code = 0 ) {
      parent::__construct ( $msg, $code );
  }
}
class ArgumentException extends Exception {
	public function __construct ( $msg = '', $code = 0 ) {
      parent::__construct ( $msg, $code );
  }
}
class InvalidTableDefinitionException extends Exception {
	public function __construct ( $msg = '', $code = 0 ) {
      parent::__construct ( $msg, $code );
  }
}

class InvalidColumnTypeException extends Exception {
	public function __construct ( $msg = '', $code = 0 ) {
      parent::__construct ( $msg, $code );
  }
}
class MissingAdapterTypeException extends Exception {
	public function __construct ( $msg = '', $code = 0 ) {
      parent::__construct ( $msg, $code );
  }
}
class SQLException extends Exception {
	public function __construct ( $msg = '', $code = 0 ) {
      parent::__construct ( $msg, $code );
  }
}


?>