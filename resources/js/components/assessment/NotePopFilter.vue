<template>
    <Popover v-model:open="show">
        <PopoverTrigger as-child>
            <Button variant="outline"> <AlignJustify /> Filter </Button>
        </PopoverTrigger>
        <PopoverContent class="w-80">
            <div class="relative items-center">
                <Input id="search" type="text" placeholder="Search Text..." class="pl-10" v-model="form.search" />
                <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                    <Search class="size-6 text-muted-foreground" />
                </span>
            </div>
            <Select v-model="form.type">
                <SelectTrigger class="w-[180px]">
                    <SelectValue placeholder="Select Type" />
                </SelectTrigger>
                <SelectContent>
                    <SelectGroup>
                        <SelectLabel>Type</SelectLabel>
                        <SelectItem v-for="(item, index) in NoteType" :key="index" :value="item"> {{ item }} </SelectItem>
                    </SelectGroup>
                </SelectContent>
            </Select>

            <Select v-model="form.status">
                <SelectTrigger class="w-[180px]">
                    <SelectValue placeholder="Select Status" />
                </SelectTrigger>
                <SelectContent>
                    <SelectGroup>
                        <SelectLabel>Status</SelectLabel>
                        <SelectItem v-for="(item, index) in NoteStatus" :key="index" :value="item"> {{ item }} </SelectItem>
                    </SelectGroup>
                </SelectContent>
            </Select>

            <Popover>
                <PopoverTrigger as-child>
                    <Button variant="outline" :class="cn('w-[280px] justify-start text-left font-normal', !dateRange && 'text-muted-foreground')">
                        <CalendarIcon class="mr-2 h-4 w-4" />
                        <template v-if="dateRange.start">
                            <template v-if="dateRange.end">
                                {{ df.format(dateRange.start.toDate(getLocalTimeZone())) }} -
                                {{ df.format(dateRange.end.toDate(getLocalTimeZone())) }}
                            </template>

                            <template v-else>
                                {{ df.format(dateRange.start.toDate(getLocalTimeZone())) }}
                            </template>
                        </template>
                        <template v-else> Pick a date </template>
                    </Button>
                </PopoverTrigger>
                <PopoverContent class="w-auto p-0">
                    <RangeCalendar
                        v-model="dateRange"
                        initial-focus
                        :number-of-months="2"
                        @update:start-value="(startDate) => (dateRange.start = startDate)"
                    />
                </PopoverContent>
            </Popover>

            <Button class="cursor-pointer" @click="search" variant="secondary">Search</Button>
            <Button class="cursor-pointer" @click="clear" variant="destructive">Clear</Button>
        </PopoverContent>
    </Popover>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { RangeCalendar } from '@/components/ui/range-calendar';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import { cn } from '@/lib/utils';
import { NoteStatus, NoteType } from '@/types/note';
import { CalendarDate, DateFormatter, getLocalTimeZone } from '@internationalized/date';
import { AlignJustify, Calendar as CalendarIcon, Search } from 'lucide-vue-next';
import { DateRange, DateValue } from 'reka-ui';
import { reactive, type Ref, ref } from 'vue';

const emits = defineEmits<{
    (e: 'search', data: any): void;
}>();

const now = new Date();
const today = new CalendarDate(now.getFullYear(), now.getMonth() + 1, now.getDate());
const show = ref(false);
const form = reactive({
    search: '',
    type: '',
    status: '',
    start: '',
    end: '',
});

const df = new DateFormatter('en-US', {
    dateStyle: 'medium',
});

const dateRange = ref({
    start: today.subtract({ days: 7 }),
    end: today,
}) as Ref<DateRange>;

const search = () => {
    emits('search', {
        search: form.search || '',
        type: form.type || '',
        status: form.status || '',
        start: dateRange.value.start ? parseDateValueToYMD(dateRange.value.start) : '',
        end: dateRange.value.end ? parseDateValueToYMD(dateRange.value.end) : '',
    });
    show.value = false;
};

const clear = () => {
    form.search = '';
    form.type = '';
    form.status = '';
    form.start = '';
    form.end = '';
    emits('search', form);
    show.value = false;
};

function parseDateValueToYMD(date: DateValue | undefined): string | undefined {
    if (!date) return undefined;

    const y = date.year;
    const m = String(date.month).padStart(2, '0');
    const d = String(date.day).padStart(2, '0');

    return `${y}-${m}-${d}`;
}
</script>
