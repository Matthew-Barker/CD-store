(function () {
    "use strict";

    /**
     * Extend the module 'CDApp' instantiated in app.js  To add a controller called
     * IndexController
     *
     * The controller is given two parameters, a name as a string and an array.
     *
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
                $scope.subTitle = 'Album listings';
                $scope.albumtitle = '';

                $scope.$on('systemInfo_album', function (ev, album) {
                    $scope.albumtitle = album.album_name;
                })

                // ------------ Login not filly implemented----------//
                $scope.loginAdmin = function (username, password) {
                    dataService.loginAdmin(username, password).then (
                        function(response){
                            //login successful
                            //create session
                        },
                        function(err){
                            $scope.status = 'Unable to load data ' + err;
                        },
                        function(notify){
                            console.log(notify);
                        }
                    );
                }

                // ------------ Logout not filly implemented----------//
                $scope.logoutAdmin = function (username, password) {
                    dataService.logoutAdmin(username, password).then (
                        function(response){
                            //logout successful
                            //delete session
                        },
                        function(err){
                            $scope.status = 'Unable to load data ' + err;
                        },
                        function(notify){
                            console.log(notify);
                        }
                    );
                }
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

                $scope.getAlbums = function (selectedGenre, searchData) {
                    console.log(selectedGenre, 'controller genre', searchData, 'controller search');
                    dataService.getAlbums(selectedGenre, searchData).then(  // then() is called when the promise is resolve or rejected
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
                    ); // end of getAlbums().then
                };

                $scope.selectedAlbum = {};

                var getGenre = function () {
                    dataService.getGenres().then(  // then() is called when the promise is resolve or rejected
                        function(response){
                            $scope.genres     = response.data;
                        },
                        function(err){
                            $scope.status = 'Unable to load data ' + err;
                        },
                        function(notify){
                            console.log(notify);
                        }
                    ); // end of getGenre().then
                };


                $scope.selectAlbum = function ($event, album,) {
                    $scope.selectedAlbum = album;
                    $location.path('/albums/' + album.album_id);
                    appData.publishInfo('album', album);
                }

                getGenre();
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

                var getNotes = function (album_id) {
                    dataService.getNotes(album_id).then(
                        function (response) {
                            $scope.noteCount = response.rowCount + ' notes';
                            $scope.notes = response.data;

                        },
                        function (err){
                            $scope.status = 'Unable to load data ' + err;
                        }
                    );  // end of getNotes().then
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
    /*.controller('fetchCtrl', ['$scope', '$http', function ($scope, $http) {
        
        // Fetch data
        $scope.fetchUsers = function(){
                        
            var searchText_len = $scope.searchText.trim().length;

            // Check search text length
            if(searchText_len > 0){
                $http({
                    method: 'post',
                    url: 'index.php',
                    subject: 'album',
                    data: {searchText:$scope.searchText}
                }).then(function successCallback(response) {
                    $scope.searchResult = response.data;
                });
            } else {
                $scope.searchResult = {};
            }
                    
        }

        // Set value to search box
        $scope.setValue = function(index,$event){
            $scope.searchText = $scope.searchResult[index].name;
            $scope.searchResult = {};
            $event.stopPropagation();
        }

        $scope.searchboxClicked = function($event){
            $event.stopPropagation();
        }

        $scope.containerClicked = function(){
            $scope.searchResult = {};
        }
    }]);*/
}());
