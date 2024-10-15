<!DOCTYPE html>
<html>

<head>
    <title>List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#fd1313">
    <meta name="msapplication-navbutton-color" content="#fd1313">
    <meta name="apple-mobile-web-app-status-bar-style" content="#fd1313">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,400i,700&amp;subset=cyrillic,cyrillic-ext" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
</head>

<body>
    <div class="container_main">
        <a class="back_top_line" href="#">
            <span>Наверх</span>
        </a>

        <div class="container_inner container_builder">
            <div class="edit_building">
                <div class="wmain">
                    <div class="edit_building_year">
                        <ul class="edit_building_year_list">
                            <li><input type="text" placeholder="Введите год"></li>
                        </ul>
                        <a class="edit_building_year_add" href="#" id="add-year"><span>Добавить год</span></a>
                    </div>
                    <ul class="edit_building_list" id="year-list">

                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#year-list').on('click', '.ebl_head', function() {
                $(this).siblings('.ebl_content').slideToggle();
            });

            $.ajax({
                url: 'http://127.0.0.1:8000/api/getYear',
                type: 'GET',
                success: function(response) {
                    response.forEach(function(year) {
                        $('#year-list').append(createYearHtml(year));
                        loadMonths(year);
                    });
                },
                error: function(error) {
                    alert('Ошибка при получении годов: ' + error.responseText);
                }
            });

            $('#add-year').on('click', function(event) {
                event.preventDefault();
                const year = $('input[type="text"]').val();
                if (year) {
                    $.ajax({
                        url: 'http://127.0.0.1:8000/api/saveYear',
                        type: 'POST',
                        data: {
                            year: year
                        },
                        success: function(response) {
                            $('#year-list').append(createYearHtml(response.year));
                            loadMonths(response.year);
                        },
                        error: function(error) {
                            alert('Ошибка при добавлении года: ' + error.responseText);
                        }
                    });
                } else {
                    alert('Введите год');
                }
            });

            $('#year-list').on('click', '.ebl_del', function(event) {
                event.preventDefault();
                const year = $(this).siblings('.ebl_year').text();
                const li = $(this).closest('li');

                $.ajax({
                    url: `http://127.0.0.1:8000/api/deleteYear/${year}`,
                    type: 'DELETE',
                    success: function() {
                        li.remove();
                    },
                    error: function(error) {
                        alert('Ошибка при удалении года: ' + error.responseText);
                    }
                });
            });

            $('#year-list').on('click', '.btn_main.add-month', function(event) {
                event.preventDefault();
                const year = $(this).closest('li').find('.ebl_year').text();
                const month = $(this).closest('.ebl_nav').find('.month-select').val();

                if (month) {
                    $.ajax({
                        url: `http://127.0.0.1:8000/api/months/${year}`,
                        type: 'POST',
                        data: {
                            month: month
                        },
                        success: (response) => {
                            const monthHtml = createMonthHtml(month);
                            $(this).closest('.ebl_content').append(monthHtml);
                        },
                        error: function(error) {
                            alert('Ошибка при добавлении месяца: ' + error.responseText);
                        }
                    });
                } else {
                    alert('Выберите месяц');
                }
            });


            $('#year-list').on('click', '.ebl_month_del', function(event) {
                event.preventDefault();
                const month = $(this).siblings('.ebl_month_name').text();
                const year = $(this).closest('.ebl_content').siblings('.ebl_head').find('.ebl_year').text();
                const monthDiv = $(this).closest('.ebl_month');

                $.ajax({
                    url: `http://127.0.0.1:8000/api/deleteMonth/${year}/${month}`,
                    type: 'DELETE',
                    success: function() {
                        monthDiv.remove();
                    },
                    error: function(error) {
                        alert('Ошибка при удалении месяца: ' + error.responseText);
                    }
                });
            });

            $('#year-list').on('click', '.ebl_img_add', function(event) {
                event.preventDefault();
                const month = $(this).closest('.ebl_month').find('.ebl_month_name').text();
                const year = $(this).closest('.ebl_content').siblings('.ebl_head').find('.ebl_year').text();
                const letter = $(this).closest('.ebl_content').find('.select_style').val();

                const fileInput = $('<input type="file" accept="image/*">');
                fileInput.on('change', function(event) {
                    const file = event.target.files[0];

                    if (file) {
                        const formData = new FormData();
                        formData.append('photo', file);
                        formData.append('month', month);
                        formData.append('year', year);
                        formData.append('letter', letter);

                        $.ajax({
                            url: 'http://127.0.0.1:8000/api/uploadPhoto',
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                const imgHtml = `<li><a class="ebl_img_del" href="#"></a><img src="http://127.0.0.1:8000${response.photo_url}" alt="Image"></li>`;
                                $(event.target).closest('.ebl_img_list').append(imgHtml);
                            },
                            error: function(error) {
                                alert('Ошибка при загрузке изображения: ' + error.responseText);
                            }
                        });
                    } else {
                        alert('Файл не выбран');
                    }
                });
                fileInput.trigger('click');
            });

            $('#year-list').on('change', '.select_style', function() {
                const selectedLetter = $(this).val();
                const monthDiv = $(this).closest('.ebl_content').find('.ebl_month');

                monthDiv.each(function() {
                    const monthName = $(this).find('.ebl_month_name').text();
                    const year = $(this).closest('.ebl_content').siblings('.ebl_head').find('.ebl_year').text();

                    $(this).find('.ebl_img_list').empty();

                    $.ajax({
                        url: `http://127.0.0.1:8000/api/photos/${year}/${monthName}/${selectedLetter}`,
                        type: 'GET',
                        success: function(photos) {
                            photos.forEach(function(photo) {
                                const imgHtml = `<li><img src="http://127.0.0.1:8000${photo.photo_url}" alt="Image"></li>`;
                                $(this).find('.ebl_img_list').append(imgHtml);
                            }.bind(this));
                            $(this).find('.ebl_img_list').append(`<li><a class="ebl_img_add" href="#">
            <span>Загрузить фото</span>
        </a></li>`);
                        }.bind(this),
                        error: function(error) {
                            alert('Ошибка при получении изображений: ' + error.responseText);
                        }
                    });
                });
            });

            function createYearHtml(year) {
                return `
        <li class="edit_building_list_act">
            <div class="ebl_head" style="cursor: pointer;">
                <div class="ebl_year">${year}</div>
                <a href="#" class="ebl_del"><span>Удалить год</span></a>
            </div>
            <div class="ebl_content">
                <div class="ebl_nav">
                    <select class="select_style" style="padding: 15px">
                        <option value="A">Выберите литеру</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                    </select>
                    <select class="month-select" style="padding: 15px">
                        <option value="">Выберите месяц</option>
                        <option value="Январь">Январь</option>
                        <option value="Февраль">Февраль</option>
                        <option value="Март">Март</option>
                        <option value="Апрель">Апрель</option>
                        <option value="Май">Май</option>
                        <option value="Июнь">Июнь</option>
                        <option value="Июль">Июль</option>
                        <option value="Август">Август</option>
                        <option value="Сентябрь">Сентябрь</option>
                        <option value="Октябрь">Октябрь</option>
                        <option value="Ноябрь">Ноябрь</option>
                        <option value="Декабрь">Декабрь</option>
                    </select>
                    <a class="btn_main add-month" style="float: none;" href="#">Добавить месяц</a>
                    <div class="ebl_format">Максимальный размер одного файла 2 Мб. Принимаются файлы jpg, gif, png</div>
                </div>
            </div>
        </li>
    `;
            }


            function createMonthHtml(month) {
                return `
    <div class="ebl_month">
    <div style="display:flex">
        <div class="ebl_month_name">${month}</div>
        <a class="ebl_month_del" href="#"><span>Удалить месяц</span></a>
        </div>
        <ul class="ebl_img_list">
            <li style="display: block;">
                <a class="ebl_img_add" href="#">
                    <span>Загрузить фото</span>
                </a>
            </li>
            <!-- Здесь будет отображаться список изображений -->
        </ul>
    </div>
    `;
            }


            function loadMonths(year) {
                $.ajax({
                    url: `http://127.0.0.1:8000/api/months/${year}`,
                    type: 'GET',
                    success: function(months) {
                        months.forEach(function(month) {
                            const monthHtml = createMonthHtml(month);
                            $('#year-list').find(`.ebl_head:contains(${year})`).siblings('.ebl_content').append(monthHtml);
                        });
                    },
                    error: function(error) {
                        alert('Ошибка при получении месяцев: ' + error.responseText);
                    }
                });
            }
        });
    </script>
</body>

</html>