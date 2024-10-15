document.addEventListener('DOMContentLoaded', () => {

    // Обработка формы добавления года
    const uploadYearForm = document.getElementById('uploadYearForm');
    if (uploadYearForm) {
        uploadYearForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Предотвращаем стандартное поведение формы

            const formData = new FormData(uploadYearForm);

            fetch('src/addYear.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                document.getElementById('uploadYearMessage').textContent = result; // Выводим результат
                // Добавляем новый год в список годов без перезагрузки
                const year = formData.get('year');
                if (year) {
                    document.querySelector('.edit_building_list').insertAdjacentHTML('beforeend', `
                        <li class="edit_building_list_act">
                            <div class="ebl_head">
                                <div class="ebl_year">${year}</div>
                                <a href="#" class="ebl_del"><span>Удалить год</span></a>
                            </div>
                            <div class="ebl_content">
                                <div class="ebl_nav">
                                    <select class="select_month" data-year="${year}">
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
                                    <a class="btn_main add-month" data-year="${year}" href="#">Добавить месяц</a>
                                </div>
                                <ul class="month-list" id="months-${year}"></ul>
                            </div>
                        </li>
                    `);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    } else {
        console.error('Форма с ID uploadYearForm не найдена.');
    }

    // Обработка добавления месяца
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('add-month')) {
            e.preventDefault();

            const year = e.target.getAttribute('data-year');
            const selectMonth = document.querySelector(`select[data-year='${year}']`);
            const monthName = selectMonth.value;

            if (monthName) {
                const formData = new FormData();
                formData.append('year', year);
                formData.append('month_name', monthName);
                formData.append('add_month', true);

                fetch('src/addYear.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        const monthList = document.getElementById(`months-${year}`);
                        monthList.insertAdjacentHTML('beforeend', `
                            <li>${monthName}
                                <a href="#" class="add-litera" data-year="${year}" data-month="${monthName}">Добавить литер</a>
                                <ul id="literas-${year}-${monthName}"></ul>
                            </li>
                        `);
                    } else {
                        alert(result.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        }
    });

    // Обработка добавления литера
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('add-litera')) {
            e.preventDefault();

            const year = e.target.getAttribute('data-year');
            const month = e.target.getAttribute('data-month');
            const litera = prompt('Введите литер:');

            if (litera) {
                const formData = new FormData();
                formData.append('year', year);
                formData.append('month', month);
                formData.append('litera', litera);
                formData.append('add_litera', true);

                fetch('src/addYear.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success') {
                        const literaList = document.getElementById(`literas-${year}-${month}`);
                        literaList.insertAdjacentHTML('beforeend', `<li>${litera}</li>`);
                    } else {
                        alert(result.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        }
    });
});
