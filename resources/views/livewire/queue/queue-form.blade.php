<div>
    @teleport('#toolbar')
    <x-sub-header
        :title="'Formulir Antrean '. $doctorSchedule->nama .  ' ' .$subTitle"
        :description="'Jam praktek '. $practiceHours[0] . '-' . $practiceHours[1]"
    />
    @endteleport
{{--    <x-tab-bar :items="$tabBarItems"/>--}}
    <x-offline-alert/>
    <livewire:dynamic-component
        :is="$activeTabBar"
        :key="$activeTabBar"
        :passData="$passData"
    />
</div>
