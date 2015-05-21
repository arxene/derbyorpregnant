var quizControllers = angular.module( 'quizControllers', [] );

quizControllers.controller( 'InProgressCtrl', ['$scope', '$http', 'quizSvc',
  function( $scope, $http, quizSvc ) {
    $scope.currentQuestion = [];
    $scope.currentIndex = 0;
    $scope.questionAnswered = false;
    $scope.isAnswerCorrect = false;
    $scope.numCorrect = quizSvc.numCorrect;
    $scope.questions = [];
    
    /* Load list of questions */
    $http.get( 'quizQuestions.json' ).
      success( function( data ) {
        $scope.questions = data;
        $scope.currentQuestion = $scope.questions[0];
      } ).
      error( function( data ) {
        console.log( "Error fetching questions." );
      } );
  
  
    /* Check whether their answer was right */
    $scope.checkAnswer = function( userAnswer ) {
      $scope.questionAnswered = true;
      $scope.isAnswerCorrect = userAnswer === $scope.currentQuestion.answer;
      
      if ( $scope.isAnswerCorrect ) {
        quizSvc.numCorrect++;
        $scope.numCorrect = quizSvc.numCorrect;
      }
    };
    
    /* Load up the next question or end game if no more questions left */
    $scope.getNextQuestion = function() {
      $scope.currentIndex++;
      
      if ( $scope.currentIndex < $scope.questions.length ) {
        $scope.currentQuestion = $scope.questions[ $scope.currentIndex ];
        $scope.questionAnswered = false;
      } else {
        $scope.endGame();
      }
    };
    
    $scope.endGame = function() {
      console.log( "Game over. Here your score will be displayed, a button to play again, a share button for social media shit, and a button to add new quiz questions." );
      
      window.location.href = "#/endgame";
    };
  }] );
  
quizControllers.controller( 'EndGameCtrl', ['$scope', 'quizSvc',
  function ( $scope, quizSvc ) {
    $scope.numCorrect = quizSvc.numCorrect;
  }] );
