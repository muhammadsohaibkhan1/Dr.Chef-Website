var exerciseId

function setExerciseId (id) {
    exerciseId = id
}

function setExerciseName (excName) {
    exerciseName = excName
    document.getElementById('exerciseName').textContent = exerciseName
}

function burnedCalories () {
    var weight = document.getElementById('user_weight').value
    var met = document.getElementById('met_' + exerciseId).value
    var time = document.getElementById('exercise_time').value

    var caloriesBurned = Math.floor((weight * met * 3.5 * time) / 200)

    document.getElementById('result').innerHTML =
        'You have burned ' + caloriesBurned + ' calories!'
    document.getElementById('burned_calories').value = caloriesBurned
    document.getElementById('add').style.display = 'inline'
}

function clearInputs () {
    document.getElementById('exercise_time').value = ''
    document.getElementById('burned_calories').value = ''
    document.getElementById('result').innerHTML = ''
    document.getElementById('add').style.display = 'none'
}
