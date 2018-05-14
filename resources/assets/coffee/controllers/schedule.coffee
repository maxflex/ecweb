angular
    .module 'App'
    .controller 'Schedule', ($scope) ->
        bindArguments($scope, arguments)

        $scope.getDateStringFromMonth = (month) ->
            year = if month >= 9 then 2018 else 2019
            month = ('0' + month) if month < 10
            return "#{year}-#{month}-01"

        $scope.getTitle = (month) ->
            moment($scope.getDateStringFromMonth(month)).format('MMMM YYYY')

        $scope.getDateString = (date) ->
            moment(date).format('YYYY-MM-DD')

        $scope.calendar = {}
        $scope.getCalendar = (month) ->
            return $scope.calendar[month] if $scope.calendar.hasOwnProperty(month)
            date = $scope.getDateStringFromMonth(month)
            console.log(date)
            startWeek = moment(date).startOf('month').week()
            endWeek = moment(date).endOf('month').week()
            # декабрь fix
            endWeek = (startWeek + 5) if startWeek > endWeek
            calendar = []
            week = startWeek
            while week <= endWeek
                calendar.push Array(7).fill(0).map((n, i) => moment(date).week(week).startOf('week').clone().add(n + i, 'day').toDate())
                week++
            calendar = calendar.slice(3, 5) if month == 9
            $scope.calendar[month] = calendar
            return calendar

        $scope.outMonth = (day, month) ->
            date = $scope.getDateStringFromMonth(month)
            return moment(day).format('M') isnt moment(date).format('M')
