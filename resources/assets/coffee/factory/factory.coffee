angular.module('App')
    .value 'Grades',
        1: '1 класс'
        2: '2 класс'
        3: '3 класс'
        4: '4 класс'
        5: '5 класс'
        6: '6 класс'
        7: '7 класс'
        8: '8 класс'
        9: '9 класс'
        10: '10 класс'
        11: '11 класс'

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
    .value 'Branches',
        TRG:
            code:'TRG',
            full:'Тургеневская',
            short:'ТУР',
            address:'Мясницкая 40с1',
            color:'#FBAA33',
        PVN:
            code:'PVN',
            full:'Проспект Вернадского',
            short:'ВЕР',
            address:'',
            color:'#EF1E25',
        BGT:
            code:'BGT',
            full:'Багратионовская',
            short:'БАГ',
            address:'',
            color:'#019EE0',
        IZM:
            code:'IZM',
            full:'Измайловская',
            short:'ИЗМ',
            address:'',
            color:'#0252A2',
        OPL:
            code:'OPL',
            full:'Октябрьское поле',
            short:'ОКТ',
            address:'',
            color:'#B61D8E',
        RPT:
            code:'RPT',
            full:'Рязанский Проспект',
            short:'РЯЗ',
            address:'',
            color:'#B61D8E',
        VKS:
            code:'VKS',
            full:'Войковская',
            short:'ВОЙ',
            address:'',
            color:'#029A55',
        ORH:
            code:'ORH',
            full:'Орехово',
            short:'ОРЕ',
            address:'',
            color:'#029A55',
        UJN:
            code:'UJN',
            full:'Южная',
            short:'ЮЖН',
            address:'',
            color:'#ACADAF',
        PER:
            code:'PER',
            full:'Перово',
            short:'ПЕР',
            address:'',
            color:'#FFD803',
        KLG:
            code:'KLG',
            full:'Калужская',
            short:'КЛЖ',
            address:'Научный проезд 8с1',
            color:'#C07911',
        BRT:
            code:'BRT',
            full:'Братиславская',
            short:'БРА',
            address:'',
            color:'#B1D332',
        MLD:
            code:'MLD',
            full:'Молодежная',
            short:'МОЛ',
            address:'',
            color:'#0252A2',
        VLD:
            code:'VLD',
            full:'Владыкино',
            short:'ВЛА',
            address:'',
            color:'#ACADAF',
