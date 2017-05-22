angular.module('App')
    # Русский язык	64,3
    # Математика	51,9
    # Химия	56,1
    # Биология	52,8
    # Физика	51,2
    # Информатика	53
    # История	48,1
    # География	49,6
    # Английский язык	64,2
    # средний балл по Москве
    .value 'AvgScores',
        '1-11-1': 51.9
        '2-11': 51.2
        '3-11': 56.1
        '4-11': 52.8
        '5-11': 53
        '6-11': 64.3
        '7-11': 56.9
        '8-11': 53.3
        '9-11': 48.1
        '10-11': 64.2
        '11-11': 49.6
    .value 'Grades',
        # 1: '1 класс'
        # 2: '2 класс'
        # 3: '3 класс'
        # 4: '4 класс'
        # 5: '5 класс'
        # 6: '6 класс'
        # 7: '7 класс'
        # 8: '8 класс'
        9: '9 класс'
        10: '10 класс'
        11: '11 класс'
        # 14: 'экстернат'
    .value 'Subjects',
        all:
            1: 'математика',
            2: 'физика',
            3: 'химия',
            4: 'биология',
            5: 'информатика'
            6: 'русский',
            7: 'литература',
            8: 'обществознание',
            9: 'история',
            10: 'английский',
            11: 'география',
        ,
        full:
            1: 'Математика'
            2: 'Физика'
            3: 'Химия'
            4: 'Биология'
            5: 'Информатика'
            6: 'Русский язык'
            7: 'Литература'
            8: 'Обществознание'
            9: 'История'
            10: 'Английский язык'
            11: 'География'
        dative:
            1: 'математике'
            2: 'физике'
            3: 'химии'
            4: 'биологии'
            5: 'информатике'
            6: 'русскому языку'
            7: 'литературе'
            8: 'обществознанию'
            9: 'истории'
            10: 'английскому языку'
            11: 'географии'
        short: ['М', 'Ф', 'Р', 'Л', 'А', 'Ис', 'О', 'Х', 'Б', 'Ин', 'Г'],
        three_letters:
            1: 'МАТ'
            2: 'ФИЗ'
            3: 'ХИМ'
            4: 'БИО'
            5: 'ИНФ'
            6: 'РУС'
            7: 'ЛИТ'
            8: 'ОБЩ'
            9: 'ИСТ'
            10: 'АНГ'
            11: 'ГЕО'
        short_eng: ['math', 'phys', 'rus', 'lit', 'eng', 'his', 'soc', 'chem', 'bio', 'inf', 'geo']
