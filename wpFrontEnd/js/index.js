angular.module('app', [])
	.controller('PageCtrl', ['$scope', function($scope){
		jQuery.get('http://localhost:8888/wpPortfolio/wp-json/posts?type=project', 
			function(projects){
				$scope.$apply(function(){
					$scope.projects = projects;
				});
			}
		);

		function JSONTest( urls ){
			angular.forEach(urls, function(url){
				jQuery.get(url, 
					function(data){
						console.log('results ('+url+') = ', data);
					}
				);
			});
		}

		JSONTest([
			// 'http://localhost:8888/wpPortfolio/wp-json/posts/types',
			// 'http://localhost:8888/wpPortfolio/wp-json/posts?type=project',
			// 'http://localhost:8888/wpPortfolio/wp-json/posts/10',

			// 'https://api.linkedin.com/v1/people/id=55739261',
			// 'https://api.linkedin.com/v1/people/url=colinsharp1',
		])

		// jQuery.get('https://api.linkedin.com/v1/people/id=55739261', 
		// 	function(data){
		// 		console.log(data)
		// 	}
		// );
		
	}])