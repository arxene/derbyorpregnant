var quizControllers = angular.module( 'quizControllers', [] );

quizControllers.controller( 'InProgressCtrl', ['$scope', '$http', 'questionLoadSvc',
  function( $scope, $http, questionLoadSvc ) {
    $scope.currentQuestion = [];
    $scope.currentIndex = 0;
    $scope.questionAnswered = false;
    $scope.isAnswerCorrect = false;
    $scope.questions = [];
    $scope.currentAnswer = "";
    $scope.gameOver = false;
    $scope.errorMsg = "";
    
    /**
     * Load list of 10 randomized questions
     * Shuffle list then grab first 10 items, or all if less than 10 items exist
     */
    $scope.initGame = function() {
      // only fetch JSON question file once
      if ( !questionLoadSvc.questions.length ) {
        var svcResult = questionLoadSvc.async().then( function( d ) {
          if (typeof d === 'string' && d.toLowercase().startsWith("sqlstate"))
          {
            $scope.errorMsg = "Unable to get quiz questions. Sorry!";
            return false;
          }
          
          questionLoadSvc.questions = d;
          
          /* shuffle and return here on first load of questions because if questions
           * are waited to be shuffled outside of this async block, then it tries to shuffle
           * before the questions have been loaded
           */ 
          shuffleQuestions();
          return true;
        } );
      }
      
      shuffleQuestions();
    };
    $scope.initGame(); // run right away when game-in-progress.html loads
    
    function shuffleQuestions() {
      // shuffle the questions every time to get a new random list of 10
      var shuffledQuestions = shuffle( questionLoadSvc.questions );
      $scope.questions = shuffledQuestions.length >= 10 ? shuffledQuestions.slice( 0, 10 ) : shuffledQuestions;
      $scope.currentQuestion = $scope.questions[0];
    }
   
    // Fisher-Yates shuffle algorithm to randomly shuffle array
    function shuffle( array ) {
      for ( var i = array.length - 1; i > 0; i-- ) {
        var j = Math.floor( Math.random() * ( i + 1 ) );
        var temp = array[i];
        array[i] = array[j];
        array[j] = temp;
      }
      return array;
    }
  
    /* Check whether their answer was right */
    $scope.checkAnswer = function( userAnswer ) {
      $scope.questionAnswered = true;
      $scope.isAnswerCorrect = userAnswer === $scope.currentQuestion.answer;
      
      if ( $scope.isAnswerCorrect ) {
        questionLoadSvc.numCorrect++;
      }
      
      var btnClass = $scope.isAnswerCorrect ? "btn-success" : "btn-danger";
      var whichBtn = userAnswer === 'derby' ? "#btnLeft" : "#btnRight";
      $(whichBtn).addClass(btnClass);
      
      $scope.currentAnswer = userAnswer;
      
      // if this is the last question, hide Next button and show link to see final score
      if( $scope.currentIndex + 1 >= $scope.questions.length ) {
        $scope.gameOver = true;
      }
    };
    
    /* Load up the next question or end game if no more questions left */
    $scope.getNextQuestion = function() {
      $scope.currentIndex++;
      $scope.currentQuestion = $scope.questions[ $scope.currentIndex ];
      $scope.questionAnswered = false;
      
      // reset button style
      var btnClass = $scope.isAnswerCorrect ? "btn-success" : "btn-danger";
      var whichBtn = $scope.currentAnswer === 'derby' ? "#btnLeft" : "#btnRight";
      $(whichBtn).removeClass(btnClass);
    };
  }] );
  
quizControllers.controller( 'EndGameCtrl', ['$scope', 'questionLoadSvc',
  function ( $scope, questionLoadSvc ) {
    $scope.numCorrect = questionLoadSvc.numCorrect;
    questionLoadSvc.numCorrect = 0; // set to 0 in case they play again
  }] );
