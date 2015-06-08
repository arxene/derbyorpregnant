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
      when('/addfood', {
        templateUrl: 'views/add-food.php'
      }).
      when('/404', {
        templateUrl: 'views/404.html'
      }).
      otherwise({
        redirectTo: '/404'
      });
  }]);

quizApp.factory('questionLoadSvc', function($http) {
  var myService = {
    numCorrect: 0,
    
    questions: [],
    
    async: function() {
      var promise = $http.post('fetch_questions.php').then( function( response ) {
        return response.data;
      } );
      
      return promise;
    }
  };
 
  return myService;
});

