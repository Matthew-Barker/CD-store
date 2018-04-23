(function () {
    "use strict";

    /**
     * Extend the module 'CourseApp' instantiated in app.js  To add a controller called
     * IndexController (on line 17)
     *
     * The controller is given two parameters, a name as a string and an array.
     * The array lists any injected objects to add and then a function which will be the
     * controller object and can contain properties and methods.
     * $scope is a built in object which refers to the application model and acts
     * as a sort of link between the controller, its data and the application's views.
     * '
     * @link https://docs.angularjs.org/guide/scope
     */
    angular.module('CDApp').
    controller('IndexController',   // controller given two params, a name and an array
        [
            '$scope',               // angular variable as a string
            'applicationData',
            function ($scope, appData) {
                // add a title property which we can refer to in our view (index.html in this example)
                $scope.title = 'CD store';
                $scope.subTitle = 'Albums';
                $scope.albumtitle = '';

                $scope.$on('systemInfo_album', function (ev, album) {
                    $scope.albumtitle = album.name;
                })
            }
        ]
    ).
    controller('AlbumController',  // create a AlbumController
        [
            '$scope',
            'dataService',
            'applicationData',
            '$location',

            function ($scope, dataService, appData, $location) {
                appData.publishInfo('album',{});

                var getAlbum = function () {
                    dataService.getAlbums().then(  // then() is called when the promise is resolve or rejected
                        function(response){
                            $scope.AlbumCount  = response.rowCount + ' albums';
                            $scope.albums     = response.data;
                        },
                        function(err){
                            $scope.status = 'Unable to load data ' + err;
                        },
                        function(notify){
                            console.log(notify);
                        }
                    ); // end of getAlbum().then
                };

                $scope.selectedAlbum = {};

                $scope.selectAlbum = function ($event, album) {
                    $scope.selectedAlbum = album;
                    $location.path('/albums/' + album.album_id);
                    appData.publishInfo('album', album);
                }

                getAlbum();  // call the method just defined
            }
        ]
    ).
    controller('AlbumTrackController',
        [
            '$scope',
            'dataService',
            '$routeParams',

            function ($scope, dataService, $routeParams){
                $scope.tracks = [ ];
                $scope.trackCount = 0;

                var getTracks = function (album_id) {
                    dataService.getTracks(album_id).then(
                        function (response) {
                            $scope.trackCount = response.rowCount + ' tracks';
                            $scope.tracks = response.data;
                        },
                        function (err){
                            $scope.status = 'Unable to load data ' + err;
                        }
                    );  // end of getTracks().then
                };

                $scope.selectedTrack = {};

                $scope.selectTrack = function ($event, track) {
                    $scope.selectedTrack = track;
                }

                // only if there has been a courseid passed in do we bother trying to get the students
                if ($routeParams && $routeParams.album_id) {
                    console.log($routeParams.album_id);
                    getTracks($routeParams.album_id);
                }

            }
        ]
    );
}());
