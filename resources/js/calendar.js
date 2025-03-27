import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";

document.addEventListener("DOMContentLoaded", function () {
    const pathname = window.location.pathname;

    if (pathname !== "/dashboard") return;

    const calendarEl = document.getElementById("calendar");
    if (!calendarEl) return;

    const calendar = new Calendar(calendarEl, {
        plugins: [interactionPlugin, dayGridPlugin, timeGridPlugin],
        initialView: "dayGridMonth",
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek",
        },
        locale: "ja",
        height: "auto",
        selectable: false,
        eventTimeFormat: {
            hour: "2-digit",
            minute: "2-digit",
            hour12: false,
        },
        // 📆 日付クリックで /schedule に遷移
        dateClick: function (info) {
            const selectedDate = info.dateStr;
            window.location.href = `/schedule?date=${selectedDate}`;
        },
        // 🔗 イベントクリックで詳細ページへ遷移
        eventClick: function (info) {
            if (info.event.id) {
                window.location.href = `/schedules/${info.event.id}`;
            }
        },
        // 📡 イベントを読み込む
        events: function (info, successCallback, failureCallback) {
            axios
                .post("/schedule-get", {
                    start_date: info.start.valueOf(),
                    end_date: info.end.valueOf(),
                })
                .then((response) => {
                    const events = response.data.map((event) => ({
                        id: event.id,
                        title: event.title,
                        start: event.start,
                        end: event.end,
                        allDay: event.start.length <= 10,
                    }));
                    successCallback(events);
                })
                .catch((error) => {
                    console.error("イベント取得エラー:", error);
                    failureCallback(error);
                });
        },
    });

    calendar.render();
});
