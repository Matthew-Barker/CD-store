(function () {
    'use strict';
    /** Service to return data*/

    angular.module('CDApp').
        service ('applicationData',
            function ($rootScope) {
                var sharedServices = {};
                sharedServices.info = {};

                //allows for loose coupling

                sharedServices.publishInfo = function (key, obj) {
                    this.info[key] = obj;
                    $rootScope.$broadcast('systemInfo_'+key, obj);
                };

                return sharedServices;
            }
        ).
        service('dataService',
        ['$q',
        '$http',
        function ($q, $http) {
            /*
                * var to hold the data base url
                */
            var urlBase = '/cm0665-assignment/server/index.php';

            // ------------ Login not filly implemented----------//

            this.loginAdmin = function (username, password) {
                var defer = $q.defer(),     //The promise
                        data = {                        // the data to be passed to the url
                            action: 'loginUser',
                            username: username,
                            password: password
                        };
                http.get(urlBase, {params: data, cache: true}).       //dot to start the chain to success()
                    success(function(response){
                        defer.resolve({
                            data: response.ResultSet.Result,         // create data property with value from response
                            rowCount: response.ResultSet.RowCount  // create rowCount property with value from response
                        });
                    }).                                                 //dot to chain to error()
                    error(function(err){
                        defer.reject(err);
                    });
                    // the call to getAlbums returns this promise which is fulfilled
                    // by the .get method .success or .failure
                    return defer.promise;           
            };

            // ------------ Logout not filly implemented----------//

            this.logoutAdmin = function (username) {
                var defer = $q.defer(),     //The promise
                        data = {                        // the data to be passed to the url
                            action: 'logoutUser',
                            username: username
                        };
                http.get(urlBase, {params: data, cache: true}).       //dot to start the chain to success()
                    success(function(response){
                        defer.resolve({
                            data: response.ResultSet.Result,         // create data property with value from response
                            rowCount: response.ResultSet.RowCount  // create rowCount property with value from response
                        });
                    }).                                                 //dot to chain to error()
                    error(function(err){
                        defer.reject(err);
                    });
                    // the call to getAlbums returns this promise which is fulfilled
                    // by the .get method .success or .failure
                    return defer.promise;           
            };

            /*
                * method to retrieve albums, or, more accurately a promise which when
                * fulfilled calls the success method
                * 
                * Function passes two params for the search and genre filters
                * Both will be passed into the url to be used in index.php
                */

            this.getAlbums = function (genre, search) {
                console.log(genre, 'service genre', search, 'service search');
                var defer = $q.defer(),     //The promise
                        data = {                        // the data to be passed to the url
                            action: 'listAlbums',
                            genre: genre,
                            search: search
                    };
                /**
                * make an ajax get call
                * chain calls to .success and .error which will resolve or reject the promise
                * @param {string} urlBase The url to call, later we'll to this to pass parameters
                * @param {object} config a configuration object, can contain parameters to pass, in this case we set cache to true
                * @return {object} promise The call returns, not data, but a promise which only if the call is successful is 'honoured'
                */

                $http.get(urlBase, {params: data, cache: false}).       //dot to start the chain to success()
                success(function(response){
                    defer.resolve({
                        data: response.ResultSet.Result,         // create data property with value from response
                        rowCount: response.ResultSet.RowCount  // create rowCount property with value from response
                    });
                }).                                                 //dot to chain to error()
                error(function(err){
                    defer.reject(err);
                });
                // the call to getAlbums returns this promise which is fulfilled
                // by the .get method .success or .failure
                return defer.promise;
            };


            /**
             * Service function to get track data for selected album from that album ID
             * 
             * @param {string} album_id The album code for the album the tracks are following
             * @returns {object} promise
             */
            this.getTracks = function (album_id) {
                var defer = $q.defer(),
                    data = {
                        action: 'listTracks',
                        id: album_id
                    };
                
                //Passes data into the URL
                $http.get(urlBase , {params: data, cache: false}).
                success(function(response){
                    defer.resolve({
                        data: response.ResultSet.Result,         // create data property with value from response
                        rowCount: response.ResultSet.RowCount // create rowCount property with value from response
                    });
                }).                                         // dot to chain to error()
                error(function(err){
                    defer.reject(err);
                });
                // the call to getTracks returns this promise which is fulfilled
                // by the .get method .success or .failure
                return defer.promise;
            };
            
            /*
            *
            * Function passes note action into the php file with the required album ID 
            */
            this.getNotes = function (album_id) {
                var defer = $q.defer(),
                    data = {
                        action: 'listNotes',
                        id: album_id
                    };

                $http.get(urlBase , {params: data, cache: false}).                  // dot to start the chain to success()
                success(function(response){
                    defer.resolve({
                        data: response.ResultSet.Result,         // create data property with value from response
                        rowCount: response.ResultSet.RowCount // create rowCount property with value from response
                    });
                }).                                         // dot to chain to error()
                error(function(err){
                    defer.reject(err);
                });
                // the call to getNotes returns this promise which is fulfilled
                // by the .get method .success or .failure
                return defer.promise;
            };

            /**
             * function to list all genres for select element
             * sends action to php file to query genres
             * 
             * @param {string}
             * @returns {object} promise
             */
            this.getGenres = function () {
                var defer = $q.defer(),
                    data = {
                        action: 'listGenres'
                    };

                $http.get(urlBase , {params: data, cache: true}).    //dot to start the chain to success()
                success(function(response){
                    defer.resolve({
                        data: response.ResultSet.Result,         // create data property with value from response
                        rowCount: response.ResultSet.RowCount // create rowCount property with value from response
                    });
                }).                                         //dot to chain to error()
                error(function(err){
                    defer.reject(err);
                });
                // the call to getAlbums returns this promise which is fulfilled
                // by the .get method .success or .failure
                return defer.promise;
            };

        }]
     )
    //.

    // filter('searchFor', function(){
    //     return function(arr, searchString){

    //         if(!searchString){
    //             return arr;
    //         }
    
    //         var result = [];
    
    //         searchString = searchString.toLowerCase();
    
    //         // Using the forEach helper method to loop through the array
    //         angular.forEach(arr, function(albums){
    
    //             if(albums.album_name.toLowerCase().indexOf(searchString) !== -1){
    //                 result.push(albums);
    //             }
    
    //         });
    
    //         return result;
    //     };
    
    // })
}());
