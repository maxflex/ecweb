(function() {
  var indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

  angular.module("App", ['ngResource', 'angular-ladda', 'angularFileUpload', 'angular-toArrayFilter', 'thatisuday.ng-image-gallery']).config([
    'ngImageGalleryOptsProvider', function(ngImageGalleryOptsProvider) {
      return ngImageGalleryOptsProvider.setOpts({
        bubbles: false,
        bubbleSize: 100,
        imgAnim: isMobile ? 'slide' : 'fadeup'
      });
    }
  ]).config([
    '$compileProvider', function($compileProvider) {
      return $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|chrome-extension|sip):/);
    }
  ]).config(function(laddaProvider) {
    return laddaProvider.setOption({
      spinnerColor: '#83b060'
    });
  }).filter('cut', function() {
    return function(value, wordwise, max, nothing, tail) {
      var lastspace;
      if (nothing == null) {
        nothing = '';
      }
      if (!value) {
        return nothing;
      }
      max = parseInt(max, 10);
      if (!max) {
        return value;
      }
      if (value.length <= max) {
        return value;
      }
      value = value.substr(0, max);
      if (wordwise) {
        lastspace = value.lastIndexOf(' ');
        if (lastspace !== -1) {
          if (value.charAt(lastspace - 1) === '.' || value.charAt(lastspace - 1) === ',') {
            lastspace = lastspace - 1;
          }
          value = value.substr(0, lastspace);
        }
      }
      return value + (tail || '…');
    };
  }).filter('hideZero', function() {
    return function(item) {
      if (item > 0) {
        return item;
      } else {
        return null;
      }
    };
  }).run(function($rootScope, $q) {
    $rootScope.dataLoaded = $q.defer();
    $rootScope.frontendStop = function(rebind_masks) {
      if (rebind_masks == null) {
        rebind_masks = true;
      }
      $rootScope.frontend_loading = false;
      $rootScope.dataLoaded.resolve(true);
      if (rebind_masks) {
        return rebindMasks();
      }
    };
    $rootScope.range = function(min, max, step) {
      var i, input;
      step = step || 1;
      input = [];
      i = min;
      while (i <= max) {
        input.push(i);
        i += step;
      }
      return input;
    };
    $rootScope.toggleEnum = function(ngModel, status, ngEnum, skip_values, allowed_user_ids, recursion) {
      var ref, ref1, ref2, status_id, statuses;
      if (skip_values == null) {
        skip_values = [];
      }
      if (allowed_user_ids == null) {
        allowed_user_ids = [];
      }
      if (recursion == null) {
        recursion = false;
      }
      if (!recursion && (ref = parseInt(ngModel[status]), indexOf.call(skip_values, ref) >= 0) && (ref1 = $rootScope.$$childHead.user.id, indexOf.call(allowed_user_ids, ref1) < 0)) {
        return;
      }
      statuses = Object.keys(ngEnum);
      status_id = statuses.indexOf(ngModel[status].toString());
      status_id++;
      if (status_id > (statuses.length - 1)) {
        status_id = 0;
      }
      ngModel[status] = statuses[status_id];
      if (indexOf.call(skip_values, status_id) >= 0 && (ref2 = $rootScope.$$childHead.user.id, indexOf.call(allowed_user_ids, ref2) < 0)) {
        return $rootScope.toggleEnum(ngModel, status, ngEnum, skip_values, allowed_user_ids, true);
      }
    };
    $rootScope.toggleEnumServer = function(ngModel, status, ngEnum, Resource) {
      var status_id, statuses, update_data;
      statuses = Object.keys(ngEnum);
      status_id = statuses.indexOf(ngModel[status].toString());
      status_id++;
      if (status_id > (statuses.length - 1)) {
        status_id = 0;
      }
      update_data = {
        id: ngModel.id
      };
      update_data[status] = status_id;
      return Resource.update(update_data, function() {
        return ngModel[status] = statuses[status_id];
      });
    };
    $rootScope.formatDateTime = function(date) {
      return moment(date).format("DD.MM.YY в HH:mm");
    };
    $rootScope.formatDate = function(date, full_year) {
      if (full_year == null) {
        full_year = false;
      }
      if (!date) {
        return '';
      }
      return moment(date).format("DD.MM.YY" + (full_year ? "YY" : ""));
    };
    $rootScope.formatDateFull = function(date) {
      return moment(date).format("D MMMM YYYY");
    };
    $rootScope.dialog = function(id) {
      $("#" + id).modal('show');
    };
    $rootScope.closeDialog = function(id) {
      $("#" + id).modal('hide');
    };
    $rootScope.onEnter = function(event, fun, prevent_default) {
      if (prevent_default == null) {
        prevent_default = true;
      }
      if (prevent_default) {
        event.preventDefault();
      }
      if (event.keyCode === 13) {
        return fun();
      }
    };
    $rootScope.ajaxStart = function() {
      ajaxStart();
      return $rootScope.saving = true;
    };
    $rootScope.ajaxEnd = function() {
      ajaxEnd();
      return $rootScope.saving = false;
    };
    $rootScope.findById = function(object, id) {
      return _.findWhere(object, {
        id: parseInt(id)
      });
    };
    $rootScope.total = function(array, prop, prop2) {
      var sum;
      if (prop2 == null) {
        prop2 = false;
      }
      sum = 0;
      $.each(array, function(index, value) {
        var v;
        v = value[prop];
        if (prop2) {
          v = v[prop2];
        }
        return sum += v;
      });
      return sum;
    };
    $rootScope.yearsPassed = function(year) {
      return moment().format("YYYY") - year;
    };
    $rootScope.deny = function(ngModel, prop) {
      return ngModel[prop] = +(!ngModel[prop]);
    };
    $rootScope.closestMetro = function(markers) {
      var closest_metro;
      closest_metro = markers[0].metros[0];
      markers.forEach(function(marker) {
        return marker.metros.forEach(function(metro) {
          if (metro.minutes < closest_metro.minutes) {
            return closest_metro = metro;
          }
        });
      });
      return closest_metro.station.title;
    };
    $rootScope.closestMetros = function(markers) {
      var closest_metros;
      closest_metros = [];
      markers.forEach(function(marker, index) {
        closest_metros[index] = marker.metros[0];
        return marker.metros.forEach(function(metro) {
          if (metro.minutes < closest_metros[index].minutes) {
            return closest_metros[index] = metro;
          }
        });
      });
      return closest_metros;
    };
    $rootScope.photoUrl = function(tutor) {
      if (tutor && tutor.photo_exists) {
        return "https://lk.ege-repetitor.ru/img/tutors/" + tutor.id + "." + tutor.photo_extension;
      } else {
        return "https://lk.ege-repetitor.ru/img/tutors/no-profile-img.gif";
      }
    };
    $rootScope.fullName = function(tutor) {
      return tutor.last_name + ' ' + tutor.first_name + ' ' + tutor.middle_name;
    };
    $rootScope.shortenGrades = function(tutor) {
      var a, combo_end, combo_start, grade_string, grades, i, j, last_grade, limit, pairs;
      if (tutor.grades.length <= 3) {
        grades = _.clone(tutor.grades);
        if (grades.length > 1) {
          last_grade = grades.pop();
        }
        grade_string = grades.join(', ');
        if (last_grade) {
          grade_string += ' и ' + last_grade;
        }
        return grade_string + (last_grade ? ' классы' : ' класс');
      } else {
        a = _.clone(tutor.grades);
        if (a.length < 1) {
          return;
        }
        limit = a.length - 1;
        combo_end = -1;
        pairs = [];
        i = 0;
        while (i <= limit) {
          combo_start = parseInt(a[i]);
          if (combo_start > 11) {
            i++;
            combo_end = -1;
            pairs.push(combo_start);
            continue;
          }
          if (combo_start <= combo_end) {
            i++;
            continue;
          }
          j = i;
          while (j <= limit) {
            combo_end = parseInt(a[j]);
            if (combo_end >= 11) {
              break;
            }
            if (parseInt(a[j + 1]) - combo_end > 1) {
              break;
            }
            j++;
          }
          if (combo_start !== combo_end) {
            pairs.push(combo_start + '–' + combo_end + ' классы');
          } else {
            pairs.push(combo_start + ' класс');
          }
          i++;
        }
        return pairs.join(', ');
      }
    };
    $rootScope.countObj = function(obj) {
      return Object.keys(obj).length;
    };
    return $rootScope.formatBytes = function(bytes) {
      if (bytes < 1024) {
        return bytes + ' Bytes';
      } else if (bytes < 1048576) {
        return (bytes / 1024).toFixed(1) + ' KB';
      } else if (bytes < 1073741824) {
        return (bytes / 1048576).toFixed(1) + ' MB';
      } else {
        return (bytes / 1073741824).toFixed(1) + ' GB';
      }
    };
  });

}).call(this);

(function() {


}).call(this);

(function() {
  angular.module('App').controller('Cv', function($scope, $timeout, $http, Subjects, Cv) {
    bindArguments($scope, arguments);
    $timeout(function() {
      $scope.cv = {};
      return $scope.sent = false;
    });
    $scope.send = function() {
      $scope.sending = true;
      $scope.errors = {};
      return Cv.save($scope.cv, function() {
        $scope.sending = false;
        $scope.sent = true;
        return $('body').animate({
          scrollTop: $('.header').offset().top
        });
      }, function(response) {
        $scope.sending = false;
        return angular.forEach(response.data, function(errors, field) {
          var input, selector;
          $scope.errors[field] = errors;
          selector = "[ng-model$='" + field + "']";
          input = $("input" + selector + ", textarea" + selector);
          input.focus();
          if (isMobile) {
            return input.notify(errors[0], notify_options);
          }
        });
      });
    };
    $scope.isSelected = function(subject_id) {
      if (!($scope.cv && $scope.cv.subjects)) {
        return false;
      }
      return $scope.cv.subjects.indexOf(subject_id) !== -1;
    };
    $scope.selectSubject = function(subject_id) {
      if (!$scope.cv.subjects) {
        $scope.cv.subjects = [];
      }
      if ($scope.isSelected(subject_id)) {
        return $scope.cv.subjects = _.without($scope.cv.subjects, subject_id);
      } else {
        return $scope.cv.subjects.push(subject_id);
      }
    };
    return $scope.selectedSubjectsList = function() {
      var i, len, ref, ref1, ref2, subject_id, subjects;
      if (!((ref = $scope.cv) != null ? (ref1 = ref.subjects) != null ? ref1.length : void 0 : void 0)) {
        return false;
      }
      subjects = [];
      ref2 = $scope.cv.subjects;
      for (i = 0, len = ref2.length; i < len; i++) {
        subject_id = ref2[i];
        subjects.push($scope.Subjects[subject_id].name);
      }
      return subjects.join(', ');
    };
  });

}).call(this);

(function() {
  angular.module('App').controller('Empty', function($scope) {
    bindArguments($scope, arguments);
    return $scope.gallery = {};
  });

}).call(this);

(function() {
  angular.module('App').controller('Order', function($scope, $timeout, $http, Grades, Subjects, Request) {
    bindArguments($scope, arguments);
    $timeout(function() {
      return $scope.order = {};
    });
    $scope.request = function() {
      $scope.sending = true;
      $scope.errors = {};
      return Request.save($scope.order, function() {
        $scope.sending = false;
        $scope.sent = true;
        return $('body').animate({
          scrollTop: $('.header').offset().top
        });
      }, function(response) {
        $scope.sending = false;
        return angular.forEach(response.data, function(errors, field) {
          var input, selector;
          $scope.errors[field] = errors;
          selector = "[ng-model$='" + field + "']";
          input = $("input" + selector + ", textarea" + selector);
          input.focus();
          if (isMobile) {
            return input.notify(errors[0], notify_options);
          }
        });
      });
    };
    $scope.isSelected = function(subject_id) {
      if (!($scope.order && $scope.order.subjects)) {
        return false;
      }
      return $scope.order.subjects.indexOf(subject_id) !== -1;
    };
    $scope.selectSubject = function(subject_id) {
      if (!$scope.order.subjects) {
        $scope.order.subjects = [];
      }
      if ($scope.isSelected(subject_id)) {
        return $scope.order.subjects = _.without($scope.order.subjects, subject_id);
      } else {
        return $scope.order.subjects.push(subject_id);
      }
    };
    return $scope.selectedSubjectsList = function() {
      var i, len, ref, ref1, ref2, subject_id, subjects;
      if (!((ref = $scope.order) != null ? (ref1 = ref.subjects) != null ? ref1.length : void 0 : void 0)) {
        return false;
      }
      subjects = [];
      ref2 = $scope.order.subjects;
      for (i = 0, len = ref2.length; i < len; i++) {
        subject_id = ref2[i];
        subjects.push($scope.Subjects[subject_id].name);
      }
      return subjects.join(', ');
    };
  });

}).call(this);

(function() {
  angular.module('App').controller('Programs', function($scope) {});

}).call(this);

(function() {
  angular.module('App').constant('REVIEWS_PER_PAGE', 5).controller('Reviews', function($scope, $timeout, $http, Subjects) {
    var search;
    bindArguments($scope, arguments);
    $timeout(function() {
      $scope.reviews = [];
      $scope.page = 1;
      $scope.has_more_pages = true;
      return search();
    });
    $scope.nextPage = function() {
      $scope.page++;
      return search();
    };
    return search = function() {
      $scope.searching = true;
      return $http.get('api/reviews', {
        page: $scope.page
      }).then(function(response) {
        console.log(response);
        $scope.searching = false;
        $scope.reviews = $scope.reviews.concat(response.data.reviews);
        return $scope.has_more_pages = response.data.has_more_pages;
      });
    };
  });

}).call(this);

(function() {
  angular.module('App').constant('REVIEWS_PER_PAGE', 5).controller('Tutors', function($scope, $timeout, $http, $sce, Tutor, REVIEWS_PER_PAGE, Subjects) {
    var filter, filter_used, search, search_count;
    bindArguments($scope, arguments);
    search_count = 0;
    $scope.profilePage = function() {
      return RegExp(/^\/tutors\/[\d]+$/).test(window.location.pathname);
    };
    $timeout(function() {
      initYoutube();
      if (!$scope.profilePage()) {
        return $scope.filter();
      }
    });
    $scope.reviews = function(tutor, index) {
      if (tutor.all_reviews === void 0) {
        tutor.all_reviews = Tutor.reviews({
          id: tutor.id
        }, function(response) {
          return $scope.showMoreReviews(tutor);
        });
      }
      return $scope.toggleShow(tutor, 'show_reviews', 'reviews', false);
    };
    $scope.showMoreReviews = function(tutor, index) {
      var from, to;
      tutor.reviews_page = !tutor.reviews_page ? 1 : tutor.reviews_page + 1;
      from = (tutor.reviews_page - 1) * REVIEWS_PER_PAGE;
      to = from + REVIEWS_PER_PAGE;
      return tutor.displayed_reviews = tutor.all_reviews.slice(0, to);
    };
    $scope.reviewsLeft = function(tutor) {
      var reviews_left;
      if (!tutor.all_reviews || !tutor.displayed_reviews) {
        return;
      }
      reviews_left = tutor.all_reviews.length - tutor.displayed_reviews.length;
      if (reviews_left > REVIEWS_PER_PAGE) {
        return REVIEWS_PER_PAGE;
      } else {
        return reviews_left;
      }
    };
    filter_used = false;
    $scope.filter = function() {
      $scope.tutors = [];
      $scope.page = 1;
      if (filter_used) {
        return filter();
      } else {
        filter();
        return filter_used = true;
      }
    };
    filter = function() {
      return search();
    };
    $scope.nextPage = function() {
      $scope.page++;
      return search();
    };
    search = function() {
      $scope.searching = true;
      return Tutor.search({
        filter_used: filter_used,
        page: $scope.page,
        search: $scope.search
      }, function(response) {
        search_count++;
        $scope.searching = false;
        $scope.data = response;
        return $scope.tutors = $scope.tutors.concat(response.data);
      });
    };
    $scope.video = function(tutor) {
      if ($scope.video_link !== tutor.video_link) {
        console.log('Setting url to ' + tutor.video_link);
        $scope.video_link = tutor.video_link;
      }
      return openModal('video');
    };
    $scope.videoLink = function() {
      return $sce.trustAsResourceUrl("https://www.youtube.com/embed/" + $scope.video_link + "?enablejsapi=1&rel=0&amp;showinfo=0");
    };
    $scope.videoDuration = function(tutor) {
      var duration, format;
      duration = parseInt(tutor.video_duration);
      format = duration >= 60 ? 'm мин s сек' : 's сек';
      return moment.utc(duration * 1000).format(format);
    };
    $scope.toggleShow = function(tutor, prop, iteraction_type, index) {
      if (index == null) {
        index = null;
      }
      if (tutor[prop]) {
        return $timeout(function() {
          return tutor[prop] = false;
        }, $scope.mobile ? 400 : 0);
      } else {
        return tutor[prop] = true;
      }
    };
    return $scope.popup = function(id, tutor, fn, index) {
      if (tutor == null) {
        tutor = null;
      }
      if (fn == null) {
        fn = null;
      }
      if (index == null) {
        index = null;
      }
      openModal(id);
      if (tutor !== null) {
        $scope.popup_tutor = tutor;
      }
      if (fn !== null) {
        return $timeout(function() {
          return $scope[fn](tutor, index);
        });
      }
    };
  });

}).call(this);

(function() {
  angular.module('App').directive('academic', function() {
    return {
      restrict: 'E',
      template: "{{ year }}–{{ +(year) + 1 }}",
      scope: {
        year: '='
      }
    };
  });

}).call(this);

(function() {
  angular.module('App').directive('digitsOnly', function() {
    return {
      restrics: 'A',
      require: 'ngModel',
      link: function($scope, $element, $attr, $ctrl) {
        var filter, ref;
        filter = function(value) {
          var new_value;
          if (!value) {
            return void 0;
          }
          new_value = value.replace(/[^0-9]/g, '');
          if (new_value !== value) {
            $ctrl.$setViewValue(new_value);
            $ctrl.$render();
          }
          return value;
        };
        return (ref = $ctrl.$parsers) != null ? ref.push(filter) : void 0;
      }
    };
  });

}).call(this);

(function() {
  angular.module('App').directive('errors', function() {
    return {
      restrict: 'E',
      templateUrl: 'directives/errors',
      scope: {
        model: '@'
      },
      controller: function($scope, $element, $attrs) {
        $scope.only_first = $attrs.hasOwnProperty('onlyFirst');
        return $scope.getErrors = function() {
          var errors;
          if ($scope.$parent.errors === void 0) {
            return;
          }
          errors = $scope.$parent.errors[$scope.model];
          if ($scope.only_first) {
            return [errors[0]];
          } else {
            return errors;
          }
        };
      }
    };
  });

}).call(this);

(function() {


}).call(this);

(function() {
  angular.module('App').directive('ngPhone', function() {
    return {
      restrict: 'A',
      link: function($scope, element) {
        return $(element).mask("+7 (999) 999-99-99", {
          autoclear: false
        });
      }
    };
  });

}).call(this);

(function() {
  angular.module('App').directive('plural', function() {
    return {
      restrict: 'E',
      scope: {
        count: '=',
        type: '@',
        noneText: '@'
      },
      templateUrl: '/directives/plural',
      controller: function($scope, $element, $attrs, $timeout) {
        $scope.textOnly = $attrs.hasOwnProperty('textOnly');
        $scope.hideZero = $attrs.hasOwnProperty('hideZero');
        return $scope.when = {
          'age': ['год', 'года', 'лет'],
          'student': ['ученик', 'ученика', 'учеников'],
          'minute': ['минуту', 'минуты', 'минут'],
          'hour': ['час', 'часа', 'часов'],
          'day': ['день', 'дня', 'дней'],
          'rubbles': ['рубль', 'рубля', 'рублей'],
          'client': ['клиент', 'клиента', 'клиентов'],
          'mark': ['оценки', 'оценок', 'оценок'],
          'review': ['отзыв', 'отзыва', 'отзывов'],
          'request': ['заявка', 'заявки', 'заявок'],
          'profile': ['анкета', 'анкеты', 'анкет'],
          'address': ['адрес', 'адреса', 'адресов'],
          'person': ['человек', 'человека', 'человек'],
          'ton': ['тонна', 'тонны', 'тонн'],
          'yacht': ['яхта', 'яхты', 'яхт']
        };
      }
    };
  });

}).call(this);

(function() {
  angular.module('App').directive('programItem', function() {
    return {
      templateUrl: '/directives/program',
      scope: {
        item: '=',
        level: '=?',
        levelstring: '='
      },
      controller: function($timeout, $element, $scope) {
        if (!$scope.level) {
          $scope.level = 0;
        }
        return $scope.getChildLevelString = function(child_index) {
          var str;
          str = $scope.levelstring ? $scope.levelstring : '';
          return str + (child_index + 1) + '.';
        };
      }
    };
  });

}).call(this);

(function() {
  angular.module('App').directive('requestForm', function() {
    return {
      replace: true,
      scope: {
        tutor: '=',
        sentIds: '=',
        index: '='
      },
      templateUrl: function(elem, attrs) {
        if (attrs.hasOwnProperty('mobile')) {
          return 'directives/request-form-mobile';
        } else {
          return 'directives/request-form';
        }
      },
      controller: function($scope, $element, $timeout, $rootScope, Request, Sources) {
        var trackDataLayer;
        $scope.request = function() {
          if ($scope.tutor.request === void 0) {
            $scope.tutor.request = {};
          }
          $scope.tutor.request.tutor_id = $scope.tutor.id;
          return Request.save($scope.tutor.request, function() {
            $scope.tutor.request_sent = true;
            $scope.$parent.StreamService.run('request', $scope.$parent.StreamService.identifySource($scope.tutor), {
              position: $scope.index || $scope.$parent.index,
              tutor_id: $scope.tutor.id
            });
            return trackDataLayer();
          }, function(response) {
            if (response.status === 422) {
              return angular.forEach(response.data, function(errors, field) {
                var selector;
                selector = "[ng-model$='" + field + "']";
                return $($element).find("input" + selector + ", textarea" + selector).focus().notify(errors[0], notify_options);
              });
            } else {
              return $scope.tutor.request_error = true;
            }
          });
        };
        return trackDataLayer = function() {
          return dataLayerPush({
            event: 'purchase',
            ecommerce: {
              currencyCode: 'RUR',
              purchase: {
                actionField: {
                  id: googleClientId(),
                  affiliation: $scope.$parent.StreamService.identifySource(),
                  revenue: $scope.tutor.public_price
                },
                products: [
                  {
                    id: $scope.tutor.id,
                    price: $scope.tutor.public_price,
                    brand: $scope.tutor.subjects,
                    category: $scope.tutor.gender + '_' + $rootScope.yearsPassed($scope.tutor.birth_year),
                    quantity: 1
                  }
                ]
              }
            }
          });
        };
      }
    };
  });

}).call(this);

(function() {
  angular.module('App').value('Grades', {
    1: '1 класс',
    2: '2 класс',
    3: '3 класс',
    4: '4 класс',
    5: '5 класс',
    6: '6 класс',
    7: '7 класс',
    8: '8 класс',
    9: '9 класс',
    10: '10 класс',
    11: '11 класс',
    14: 'экстернат'
  }).value('Subjects', {
    all: {
      1: 'математика',
      2: 'физика',
      3: 'химия',
      4: 'биология',
      5: 'информатика',
      6: 'русский',
      7: 'литература',
      8: 'обществознание',
      9: 'история',
      10: 'английский',
      11: 'география'
    },
    full: {
      1: 'Математика',
      2: 'Физика',
      3: 'Химия',
      4: 'Биология',
      5: 'Информатика',
      6: 'Русский язык',
      7: 'Литература',
      8: 'Обществознание',
      9: 'История',
      10: 'Английский язык',
      11: 'География'
    },
    dative: {
      1: 'математике',
      2: 'физике',
      3: 'химии',
      4: 'биологии',
      5: 'информатике',
      6: 'русскому языку',
      7: 'литературе',
      8: 'обществознанию',
      9: 'истории',
      10: 'английскому языку',
      11: 'географии'
    },
    short: ['М', 'Ф', 'Р', 'Л', 'А', 'Ис', 'О', 'Х', 'Б', 'Ин', 'Г'],
    three_letters: {
      1: 'МАТ',
      2: 'ФИЗ',
      3: 'ХИМ',
      4: 'БИО',
      5: 'ИНФ',
      6: 'РУС',
      7: 'ЛИТ',
      8: 'ОБЩ',
      9: 'ИСТ',
      10: 'АНГ',
      11: 'ГЕО'
    },
    short_eng: ['math', 'phys', 'rus', 'lit', 'eng', 'his', 'soc', 'chem', 'bio', 'inf', 'geo']
  });

}).call(this);

(function() {
  var apiPath, countable, updatable;

  angular.module('App').factory('Tutor', function($resource) {
    return $resource(apiPath('tutors'), {
      id: '@id',
      type: '@type'
    }, {
      search: {
        method: 'POST',
        url: apiPath('tutors', 'search')
      },
      reviews: {
        method: 'GET',
        isArray: true,
        url: apiPath('reviews')
      }
    });
  }).factory('Request', function($resource) {
    return $resource(apiPath('requests'), {
      id: '@id'
    }, updatable());
  }).factory('Cv', function($resource) {
    return $resource(apiPath('cv'), {
      id: '@id'
    }, updatable());
  });

  apiPath = function(entity, additional) {
    if (additional == null) {
      additional = '';
    }
    return ("/api/" + entity + "/") + (additional ? additional + '/' : '') + ":id";
  };

  updatable = function() {
    return {
      update: {
        method: 'PUT'
      }
    };
  };

  countable = function() {
    return {
      count: {
        method: 'GET'
      }
    };
  };

}).call(this);

(function() {
  angular.module('App').service('PhoneService', function() {
    var isFull;
    this.checkForm = function(element) {
      var phone_element, phone_number;
      phone_element = $(element).find('.phone-field');
      if (!isFull(phone_element.val())) {
        phone_element.focus().notify('номер телефона не заполнен полностью', notify_options);
        return false;
      }
      phone_number = phone_element.val().match(/\d/g).join('');
      if (phone_number[1] !== '4' && phone_number[1] !== '9') {
        phone_element.focus().notify('номер должен начинаться с 9 или 4', notify_options);
        return false;
      }
      return true;
    };
    isFull = function(number) {
      if (number === void 0 || number === "") {
        return false;
      }
      return !number.match(/_/);
    };
    return this;
  });

}).call(this);

(function() {
  angular.module('App').service('StreamService', function($http, $timeout, Stream, SubjectService, Sources) {
    this.identifySource = function(tutor) {
      if (tutor == null) {
        tutor = void 0;
      }
      if (tutor !== void 0 && tutor.is_similar) {
        return 'similar';
      }
      if (RegExp(/^\/[\d]+$/).test(window.location.pathname)) {
        return 'tutor';
      }
      if (window.location.pathname === '/request') {
        return 'help';
      }
      if (window.location.pathname === '/') {
        return 'main';
      }
      return 'serp';
    };
    this.generateEventString = function(params) {
      var parts, search;
      search = $.cookie('search');
      if (search !== void 0) {
        $.each(JSON.parse(search), function(key, value) {
          return params[key] = value;
        });
      }
      parts = [];
      $.each(params, function(key, value) {
        var where;
        switch (key) {
          case 'sort':
            switch (parseInt(value)) {
              case 2:
                value = 'maxprice';
                break;
              case 3:
                value = 'minprice';
                break;
              case 4:
                value = 'rating';
                break;
              case 5:
                value = 'bymetro';
                break;
              default:
                value = 'pop';
            }
            break;
          case 'place':
            switch (parseInt(params.place)) {
              case 1:
                where = 'tutor';
                break;
              case 2:
                where = 'client';
                break;
              default:
                where = 'any';
            }
            break;
          case 'subjects':
            value = SubjectService.getSelected(value).join(',');
        }
        if ((key === 'action' || key === 'type' || key === 'google_id' || key === 'yandex_id' || key === 'id' || key === 'hidden_filter') || !value) {
          return;
        }
        return parts.push(key + '=' + value);
      });
      return parts.join('_');
    };
    this.updateCookie = function(params) {
      if (this.cookie === void 0) {
        this.cookie = {};
      }
      $.each(params, (function(_this) {
        return function(key, value) {
          return _this.cookie[key] = value;
        };
      })(this));
      return $.cookie('stream', JSON.stringify(this.cookie), {
        expires: 365,
        path: '/'
      });
    };
    this.initCookie = function() {
      if ($.cookie('stream') !== void 0) {
        return this.cookie = JSON.parse($.cookie('stream'));
      } else {
        return this.updateCookie({
          step: 0,
          search: 0
        });
      }
    };
    this.run = function(action, type, additional) {
      if (additional == null) {
        additional = {};
      }
      if (this.cookie === void 0) {
        this.initCookie();
      }
      if (!this.initialized) {
        return $timeout((function(_this) {
          return function() {
            return _this._run(action, type, additional);
          };
        })(this), 1000);
      } else {
        return this._run(action, type, additional);
      }
    };
    this._run = function(action, type, additional) {
      var params;
      if (additional == null) {
        additional = {};
      }
      this.updateCookie({
        step: this.cookie.step + 1
      });
      params = {
        action: action,
        type: type,
        step: this.cookie.step,
        google_id: googleClientId(),
        yandex_id: yaCounter1411783.getClientID(),
        mobile: typeof isMobile === 'undefined' ? '0' : '1'
      };
      $.each(additional, (function(_this) {
        return function(key, value) {
          return params[key] = value;
        };
      })(this));
      console.log(action, type, params);
      if (action !== 'view') {
        console.log(this.generateEventString(angular.copy(params)));
      }
      if (action !== 'view') {
        dataLayerPush({
          event: 'configuration',
          eventCategory: ("action=" + action) + (type ? "_type=" + type : ""),
          eventAction: this.generateEventString(angular.copy(params))
        });
      }
      return Stream.save(params).$promise;
    };
    return this;
  });

}).call(this);

//# sourceMappingURL=app.js.map
