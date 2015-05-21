var quizApp = angular.module('quizApp', [
  'ngRoute',
  'quizControllers'
]);

quizApp.config(['$routeProvider',
  function($routeProvider) {    
    $routeProvider.
      when('/', {
        templateUrl: 'views/begin-game.html'
      }).
      when('/play', {
        templateUrl: 'views/game-in-progress.html',
        controller: 'InProgressCtrl'
      }).
      when('/endgame', {
        templateUrl: 'views/end-game.html',
        controller: 'EndGameCtrl'
      }).
      when('/404', {
        templateUrl: 'views/404.html'
      }).
      otherwise({
        redirectTo: '/404'
      });
  }]);

quizApp.factory('quizSvc', function() {
  var quizResults = {numCorrect: 0};
  
  return quizResults; 
});
