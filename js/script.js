(function (OC, window, $, undefined) {
	'use strict';

	$(document).ready(function () {

		var Bookmarks = function (baseUrl) {

			this._baseUrl = baseUrl;
			this._bookmarks = [];
			this._tree = {};

			this.loadAll =  function () {
				var deferred = $.Deferred();
				var self = this;
				$.get(this._baseUrl).done(function (bookmarks) {
					self._bookmarks = bookmarks;
					self.buildTree();
					deferred.resolve();
				}).fail(function () {
					deferred.reject();
				});
				return deferred.promise();
			};

			this.getAll = function (getTree) {
				return (getTree === true) ? this._tree : this._bookmarks;
			};

			this.buildTree = function () {
				var arr = this._bookmarks;

				var tree = [],
					mappedArr = {},
					arrElem,
					mappedElem;

				// First map the nodes of the array to an object -> create a hash table.
				for(var i = 0, len = arr.length; i < len; i++) {
					arrElem = arr[i];
					mappedArr[arrElem.id] = arrElem;
					mappedArr[arrElem.id]['children'] = [];
				}

				for (var id in mappedArr) {
					if (mappedArr.hasOwnProperty(id)) {
						mappedElem = mappedArr[id];
						// If the element is not at the root level, add it to its parent array of children.
						if (mappedElem.parent_id) {
							mappedArr[mappedElem['parent_id']]['children'].push(mappedElem);
						}
						// If the element is at the root level, add it to first level elements array.
						else {
							tree.push(mappedElem);
						}
					}
				}

				this._tree = tree;

				return tree;
			}
		};

		var bookmarks = new Bookmarks(OC.generateUrl('/apps/bookmarks/bookmarks'));
		bookmarks.loadAll().done(function () {
			console.log(bookmarks.getAll(true));
		}).fail(function () {
			alert('Could not load bookmarks');
		});


	});

})(OC, window, jQuery);