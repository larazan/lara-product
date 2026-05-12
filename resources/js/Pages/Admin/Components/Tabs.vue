<script setup>
    import { ref, provide } from 'vue'
    
    const props = defineProps({
      defaultTab: {
        type: String,
        required: true,
      },
    })
    
    const activeTab = ref(props.defaultTab)
    
    const setActive = (tab) => {
      activeTab.value = tab
    }
    
    provide('activeTab', activeTab)
    provide('setActiveTab', setActive)
    </script>
    
    <template>
      <div>
        <!-- Tab headers -->
        <div class="border-b border-gray-200 flex gap-2">
          <slot name="tabs" />
        </div>
    
        <!-- Tab content -->
        <div class="mt-4">
          <slot name="panels" />
        </div>
      </div>
    </template>
    

    <!-- <Tabs defaultTab="attempts">
        <template #tabs>
          <Tab name="attempts">Attempts</Tab>
          <Tab name="users">Users</Tab>
          <Tab name="quizzes">Quizzes</Tab>
        </template>
    
        <template #panels>
          <TabPanel name="attempts">
            <LineChart
              label="Attempts"
              :labels="attemptChart.labels"
              :data="attemptChart.data"
            />
          </TabPanel>
    
          <TabPanel name="users">
            <p class="text-gray-600">User analytics here</p>
          </TabPanel>
    
          <TabPanel name="quizzes">
            <p class="text-gray-600">Quiz performance here</p>
          </TabPanel>
        </template>
      </Tabs> -->