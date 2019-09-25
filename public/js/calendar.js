const weeks = ['日', '月', '火', '水', '木', '金', '土']
  const date = new Date()
  let year = date.getFullYear()
  let month = date.getMonth() + 1
  const config = {
      show: 1,
  }

  function showCalendar(year, month) {
      for (i = 0; i < config.show; i++) {
          const startDate = new Date(year, month - 1, 1) // 月の最初の日を取得
          const endDate = new Date(year, month, 0) // 月の最後の日を取得
          const endDayCount = endDate.getDate() // 月の末日
          const lastMonthEndDate = new Date(year, month - 2, 0) // 前月の最後の日の情報
          const lastMonthendDayCount = lastMonthEndDate.getDate() // 前月の末日
          const startDay = startDate.getDay() // 月の最初の日の曜日を取得
          let dayCount = 1 // 日にちのカウント
          let calendarHtml = '' // HTMLを組み立てる変数

          calendarHtml += '<h1>' + year + '/' + month + '</h1>'
          calendarHtml += '<table>'

          // 曜日の行を作成
          for (let i = 0; i < weeks.length; i++) {
              calendarHtml += '<td>' + weeks[i] + '</td>'
          }

          // year, monthからdiaryデータを取得する
          let formData = new FormData();
          formData.append('year', year);
          formData.append('month', month);
          let diaries = [];

          fetch('/api/admin/diary/getDiaries', {
              method: 'POST',
              body: formData,
          }).then(function (response) {
              return response.clone().json();
          }).then(function (json) {
              if (json.status === 'ok') {
                  diaries = json.diaries;

                  for (let w = 0; w < 6; w++) {
                      calendarHtml += '<tr>'

                      for (let d = 0; d < 7; d++) {
                          let id = null;
                          let is_diary = false;
                          // idを取得
                          for (let i = 0; i < diaries.length; i++) {
                              let date = diaries[i]['date'];
                              let day = dayCount;
                              if (String(month).length == 1) {
                                  month = `0${month}`
                              }
                              if (String(dayCount).length == 1) {
                                  day = `0${day}`
                              }
                              if (date === `${year}-${month}-${day}`) {
                                  id = diaries[i]['id'];
                                  is_diary = true;
                                  break;
                              }
                          }
                          if (w == 0 && d < startDay) {
                              // 1行目で1日の曜日の前
                              let num = lastMonthendDayCount - startDay + d + 1
                              // ⭐︎ is-disabledをcalendar_tdにする
                              calendarHtml += `<td class="calendar_td ${is_diary ? 'diary' : ''}" data-id=${id}>${num}</td>`
                          } else if (dayCount > endDayCount) {
                              // 末尾の日数を超えた
                              let num = dayCount - endDayCount
                              // ⭐︎ is-disabledをcalendar_tdにする
                              calendarHtml += `<td class="calendar_td ${is_diary ? 'diary' : ''}" data-id=${id}>${num}</td>`
                              dayCount++
                          } else {
                              // ⭐︎ data-idを動的にする
                              calendarHtml += `<td class="calendar_td ${is_diary ? 'diary' : ''}" data-id=${id}>${dayCount}</td>`
                              dayCount++
                          }
                      }
                      calendarHtml += '</tr>'
                  }

                  calendarHtml += '</table>'

                  const sec = document.createElement('section')
                  sec.innerHTML = calendarHtml
                  document.querySelector('#calendar').appendChild(sec)

                  month++
                  if (month > 12) {
                      year++
                      month = 1
                  }
              }
          });
      }
  }

  function moveCalendar(e) {
      document.querySelector('#calendar').innerHTML = ''

      if (e.target.id === 'prev') {
          month--

          if (month < 1) {
              year--
              month = 12
          }
      }

      if (e.target.id === 'next') {
          month++

          if (month > 12) {
              year++
              month = 1
          }
      }

      showCalendar(year, month)
  }

  document.addEventListener("click", function (e) {
      if (e.target.classList.contains("calendar_td")) {
          if (e.target.dataset.id !== 'null') {
              // ページ遷移
              document.location.href = '/admin/diary/contents/' + e.target.dataset.id;
          }
      }
      if (e.target.id === 'prev') {
          moveCalendar(e);
      }
      if (e.target.id === 'next') {
          moveCalendar(e);
      }
  })

  showCalendar(year, month)