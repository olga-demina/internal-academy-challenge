import { computed, watch } from 'vue';

interface ScheduleForm {
    starts_at: string;
    ends_at: string;
}

function addOneHour(datetime: string): string {
    const [date, time] = datetime.split('T');
    const [h, m] = time.split(':').map(Number);
    const newH = (h + 1) % 24;
    const newDate = h === 23
        ? (() => { const d = new Date(date); d.setDate(d.getDate() + 1); return d.toISOString().slice(0, 10); })()
        : date;
    return `${newDate}T${String(newH).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
}

function parseLocalDatetime(s: string): number {
    const [date, time] = s.split('T');
    const [y, mo, d] = date.split('-').map(Number);
    const [h, mi] = time.split(':').map(Number);
    return new Date(y, mo - 1, d, h, mi).getTime();
}

export function useWorkshopSchedule(form: ScheduleForm) {
    watch(() => form.starts_at, (val) => {
        if (val) form.ends_at = addOneHour(val);
    });

    const duration = computed(() => {
        if (!form.starts_at || !form.ends_at) return null;
        const diff = parseLocalDatetime(form.ends_at) - parseLocalDatetime(form.starts_at);
        if (diff <= 0) return null;
        const h = Math.floor(diff / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        if (h === 0) return `${m} min`;
        if (m === 0) return `${h} h`;
        return `${h} h ${m} min`;
    });

    return { duration };
}
