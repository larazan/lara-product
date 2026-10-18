<script>
const submitQuiz = async (auto = false) => {

    if (!auto && answeredCount.value < totalQuestions.value) {
        alert('Jawab semua soal dulu!')
        return
    }

    if (loading.value) return

    loading.value = true
    clearInterval(timer)

    try {
        const formattedAnswers = Object.values(answers.value)

        console.log('SUBMIT:', {
            attempt_id: props.attempt.id,
            answers: formattedAnswers,
            auto_submit: auto,
        })

        await axios.post(route('quiz.submitAll'), {
            attempt_id: props.attempt.id,
            answers: formattedAnswers,
            auto_submit: auto,
        })

        window.location = route('quiz.results', props.attempt.id)

    } catch (e) {
        loading.value = false
        console.error(e.response?.data || e.message)
        alert('Submit gagal, cek console!')
    }
}
</script>