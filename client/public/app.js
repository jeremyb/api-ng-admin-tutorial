(function () {
    "use strict";

    var app = angular.module('myApp', ['ng-admin']);

    app.directive('customPostLink', ['$location', function ($location) {
        return {
            restrict: 'E',
            template: '<a ng-click="displayPost(entry)">View&nbsp;post</a>',
            link: function ($scope) {
                $scope.displayPost = function (entry) {
                    var post = entry.values.post;

                    $location.path('/edit/posts/' + post);
                };
            }
        };
    }]);

    app.config(function (NgAdminConfigurationProvider, Application, Entity, Field, Reference, ReferencedList, ReferenceMany, RestangularProvider, $stateProvider) {

        function truncate(value) {
            if (!value) {
                return '';
            }

            return value.length > 50 ? value.substr(0, 50) + '...' : value;
        }

        // use the custom query parameters function to format the API request correctly
        RestangularProvider.addFullRequestInterceptor(function(element, operation, what, url, headers, params) {
            // ignore id element on update
            if (operation === 'put') {
                delete element.id;
            }

            // custom pagination params
            if (operation == "getList") {
                params.page = params._page;
                delete params._page;
                delete params._perPage;
            }

            return { params: params };
        });

        RestangularProvider.addResponseInterceptor(function(data, operation, what, url, response, deferred) {
            if (operation === 'getList' && angular.isDefined(response.data._embedded)) {
                response.totalCount = response.data.total;

                return response.data._embedded.items;
            }

            return response.data;
        });

        RestangularProvider.addElementTransformer('posts', function(element) {
            if (angular.isDefined(element.tags)) {
                var tags = [];
                angular.forEach(element.tags, function (tag) {
                    tags.push(tag.id);
                });

                element.tags = tags;
            }

            return element;
        });

        var app = new Application('ng-admin backend demo')
            .baseApiUrl('http://localhost:8000/api/');

        var post    = new Entity('posts');
        var comment = new Entity('comments');
        var tag     = new Entity('tags').readOnly();

        app
            .addEntity(post)
            .addEntity(tag)
            .addEntity(comment);

        /**
         * Posts
         */

        // customize Post URL with trailing slash
        post.url(function(view, entityId) {
            return 'posts/' + (angular.isDefined(entityId) ? entityId : '');
        });

        post.menuView()
            .icon('<span class="glyphicon glyphicon-file"></span>');

        post.dashboardView()
            .title('Recent posts')
            .order(1)
            .limit(5)
            .fields([new Field('title').isDetailLink(true).map(truncate)]);

        post.listView()
            .title('All posts')
            .description('List of posts with pagination')
            .perPage(10)
            .fields([
                new Field('id').label('ID'),
                new Field('title'),
                new ReferenceMany('tags')
                    .targetEntity(tag)
                    .targetField(new Field('name'))
            ])
            .listActions(['show', 'edit', 'delete']);

        post.creationView()
            .fields([
                new Field('title')
                    .attributes({ placeholder: 'the post title' })
                    .validation({ required: true, minlength: 3, maxlength: 100 }),
                new Field('body').type('wysiwyg')
            ]);

        post.editionView()
            .title('Edit post "{{ entry.values.title }}"')
            .actions(['list', 'show', 'delete'])
            .fields([
                post.creationView().fields(),
                new ReferenceMany('tags')
                    .targetEntity(tag)
                    .targetField(new Field('name'))
                    .cssClasses('col-sm-4'),
                new ReferencedList('comments')
                    .targetEntity(comment)
                    .targetReferenceField('post')
                    .targetFields([
                        new Field('id'),
                        new Field('body').label('Comment')
                    ])
            ]);

        post.showView()
            .fields([
                new Field('id'),
                post.editionView().fields(),
                new Field('custom_action')
                    .type('template')
                    .template('<other-page-link></other-link-link>')
            ]);

        /**
         * Comments
         */

        // customize Post URL with trailing slash
        comment.url(function(view, entityId) {
            return 'comments/' + (angular.isDefined(entityId) ? entityId : '');
        });

        comment.menuView()
            .order(2)
            .icon('<span class="glyphicon glyphicon-envelope"></span>');

        comment.dashboardView()
            .title('Last comments')
            .order(2)
            .limit(5)
            .fields([
                new Field('id'),
                new Field('body').label('Comment').map(truncate),
                new Field()
                    .type('template')
                    .label('Actions')
                    .template('<custom-post-link></custom-post-link>')
            ]);

        comment.listView()
            .title('Comments')
            .perPage(10)
            .fields([
                new Field('id').label('ID'),
                new Field('createdAt').label('Posted').type('date'),
                new Field('body').map(truncate)
            ])
            .listActions(['edit', 'delete']);

        comment.creationView()
            .fields([
                new Field('createdAt').label('Posted').type('date'),
                new Field('body').type('wysiwyg'),
                new Reference('post')
                    .label('Post')
                    .map(truncate)
                    .targetEntity(post)
                    .targetField(new Field('title'))
            ]);

        comment.editionView()
            .fields(comment.creationView().fields())
            .fields([
                new Field()
                    .type('template')
                    .label('Actions')
                    .template('<custom-post-link></custom-post-link>')
            ]);

        comment.deletionView()
            .title('Deletion confirmation');

        /**
         * Tags
         */
        tag.menuView().order(3);

        tag.dashboardView()
            .title('Recent tags')
            .order(3)
            .limit(10)
            .fields([
                new Field('id').label('ID'),
                new Field('name')
            ]);

        tag.listView()
            .infinitePagination(false)
            .fields([
                tag.dashboardView().fields()
            ]);

        NgAdminConfigurationProvider.configure(app);
    });
}());