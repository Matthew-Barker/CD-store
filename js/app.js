(function () {
    "use strict";  // turn on javascript strict syntax mode

    /**
     * Start a new Application, a module in Angular
     * @param {string} ApplicationName a string which will be the name of the application
     *                 and, in fact, an object to which we add all other components
     * @param {array} dependencies An array of dependencies, the names are passed as strings
     */
    angular.module("CDApp",
        [
            'ngRoute'   // the only dependency at this stage, for routing
        ]
    ).              // note this fullstop where we chain the call to config
    config(
        [
            '$routeProvider',     // built in variable which injects functionality, passed as a string
            function($routeProvider) {
                $routeProvider.
                when('/albums', {
                    controller: 'AlbumController'
                }).
                when('/albums/:album_id', {
                    templateUrl: 'js/partials/track-list.html',
                    controller: 'AlbumTrackController'
                }).
                otherwise({
                    redirectTo: '/'
                });
            }
        ]
    );  // end of config method
}());   // end of IIFE
