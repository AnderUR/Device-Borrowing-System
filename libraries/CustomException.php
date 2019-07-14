<?php
declare(strict_types=1);

class CustomException extends Exception {
  private $logPath = "application\logs\deviceLoanLogs\log.php";
  private $customMessage = '<div class="exception textCenter">Sorry, this action caused an error.</div>';

  /*
  * Argument: Optional: String error message to display to user. Type error must be passed when instantiating customMessage.
  * Purpose: Handle device type error, since device types are hard-coded. Log the error to custom log file directory
  * Return: Formatted html error with the message received. If no message was received, default message will be used.
  */
  public function deviceTypeError(String $message = "") {
    $date = date('y-m-d h:i:sa');
    $error = "\n". $date. "\nError in ".$this->getFile()
    .": The device type passed, ".$this->getMessage()
    ." , is not a valid device type. Line ".$this->getLine()."\n";

    error_log($error, 3, $this->logPath);

    if( empty($message) ) {
      return $this->customMessage;
    } else {
      return '<div class="exception textCenter">'.$message.'</div>';
    }
  }

  /*
  * Argument: Optional: String error message to display to user and if a database error occurred, a db error array in order to log more details
  * Purpose: Can be called to handle any error or database error. Log the error to custom log file directory
  * Return: Formatted html error with the message received. If no message was received, default message will be used.
  */
  public function getCustomError(String $message = "", $dbError = 0) {
    if( $dbError != 0 ) {
      $date = date('y-m-d h:i:sa');
      $error =
       "\n" . $date
      ."\nError in " . $this->getFile()
      ."\nCode: " . $dbError['code']
      ."\nMessage: " . $dbError['message']
      ."\nTrace: \n".$this->getTraceAsString()."\n";
    } else {
      $date = date('y-m-d h:i:sa');
      $error =
       "\n". $date
      ."\nError in ".$this->getFile().": ".$this->getMessage()
      ."\nLine: ".$this->getLine()
      ."\nTrace: \n".$this->getTraceAsString()."\n";
    }

    error_log($error, 3, $this->logPath);

    if( empty($message) ) {
      return $this->customMessage;
    } else {
      return '<div class="exception textCenter">'.$message.'</div>';
    }
  }

  /*
  * Argument: Optional: String error message to display to user. Upload error must be passed when instantiating customMessage.
  * Purpose: Can be called to handle file upload error. Log the error to custom log file directory
  * Return: Formatted html error with the message received. If no message was received, default message will be used.
  */
  public function getUploadError(String $message = "") {

      $date = date('y-m-d h:i:sa');
      $error =
       "\n". $date
      ."\nError in ".$this->getFile()
      ."\nLine: ".$this->getLine()
      ."\nUpload Error: ".$this->getMessage()
      ."\nTrace: \n".$this->getTraceAsString()."\n";

    error_log($error, 3, $this->logPath);

    if( empty($message) ) {
      return $this->customMessage;
    } else {
      return '<div class="exception textCenter">'.$message.'</div>';
    }
  }
  
}
