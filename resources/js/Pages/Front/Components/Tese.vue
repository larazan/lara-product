<template>
<article
  id="block-1"
  class="relative mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300"
>
  <!-- Header -->
  <div class="mb-4 flex items-center gap-3">
    <div
      class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-base font-extrabold text-white"
    >
      1
    </div>

    <span
      class="rounded-lg border border-indigo-100 bg-indigo-50 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-indigo-600"
    >
      Pilihan Ganda
    </span>
  </div>

  <!-- Question -->
  <div class="mb-5">
    <h3 class="text-lg font-bold leading-relaxed text-slate-800">
      Urutan gerakan yang benar saat melakukan guling depan adalah...
    </h3>
  </div>

  <!-- Option A -->
  <div
    class="mb-2 rounded-lg border border-slate-200 bg-slate-50 p-3 transition-all duration-200 hover:border-indigo-300 hover:bg-indigo-50"
  >
    <label class="flex w-full cursor-pointer items-center gap-3">
      <input
        onclick="Engine(1, this.value)"
        type="radio"
        name="q1"
        value="a"
        class="h-[18px] w-[18px] cursor-pointer accent-indigo-600"
      />

      <span class="text-slate-700">
        <strong>A.</strong>
        Tengkuk, punggung, pinggang, panggul bagian belakang
      </span>
    </label>
  </div>

  <!-- Option B -->
  <div
    class="mb-2 rounded-lg border border-slate-200 bg-slate-50 p-3 transition-all duration-200 hover:border-indigo-300 hover:bg-indigo-50"
  >
    <label class="flex w-full cursor-pointer items-center gap-3">
      <input
        onclick="Engine(1, this.value)"
        type="radio"
        name="q1"
        value="b"
        class="h-[18px] w-[18px] cursor-pointer accent-indigo-600"
      />

      <span class="text-slate-700">
        <strong>B.</strong>
        Kepala, punggung, tangan, kaki
      </span>
    </label>
  </div>

  <!-- Option C -->
  <div
    class="mb-2 rounded-lg border border-slate-200 bg-slate-50 p-3 transition-all duration-200 hover:border-indigo-300 hover:bg-indigo-50"
  >
    <label class="flex w-full cursor-pointer items-center gap-3">
      <input
        onclick="Engine(1, this.value)"
        type="radio"
        name="q1"
        value="c"
        class="h-[18px] w-[18px] cursor-pointer accent-indigo-600"
      />

      <span class="text-slate-700">
        <strong>C.</strong>
        Tangan, kepala, kaki, pinggang
      </span>
    </label>
  </div>

  <!-- Option D -->
  <div
    class="rounded-lg border border-slate-200 bg-slate-50 p-3 transition-all duration-200 hover:border-indigo-300 hover:bg-indigo-50"
  >
    <label class="flex w-full cursor-pointer items-center gap-3">
      <input
        onclick="Engine(1, this.value)"
        type="radio"
        name="q1"
        value="d"
        class="h-[18px] w-[18px] cursor-pointer accent-indigo-600"
      />

      <span class="text-slate-700">
        <strong>D.</strong>
        Dahi, pundak, punggung, kaki
      </span>
    </label>
  </div>
</article>
</template>