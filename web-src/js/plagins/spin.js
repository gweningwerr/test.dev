// спиннер плагин
$.fn.spin = function(opts) {

    opts = opts || {
            lines: 12,      // Количество выводимых линий
            length: 3,      // Длина каждой линии
            width: 2,       // Ширина линии
            radius: 8,      // Радиус внутреннего круга
            color: '#333',  // #rbg или #rrggbb
            speed: 0.7,     // Вращений в секунду
            trail: 50,      // Процент следового свечения
            shadow: false   // Наличие тени у каждой линии
        };

    this.each(function() {
        var $this = $(this),
            spinner = $this.data('spinner');

        if (spinner) {
            spinner.stop();
        }

        if (opts !== false) {
            opts = $.extend({color: $this.css('color')}, opts);
            spinner = new Spinner(opts).spin(this);
            $this.data('spinner', spinner);
        }
    });

    return this;
};
