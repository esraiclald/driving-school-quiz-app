        let currentQuestionIndex = 0;
        let totalSeconds = 45 * 60;

        const questionNumberElement = document.getElementById("question-number");
        const questionText = document.getElementById("questionText");
        const btn_Next = document.getElementById("btn_Next");
        const btn_Previous = document.getElementById("btn_Previous");
        const choicesBox = document.getElementById("choicesBox");
        const questionsBox = document.getElementById("questionsBox");
        const progressFill = document.getElementById("progressFill");
        const scoreCounterText =document.getElementById("score-counter-text");
        const scoreElement = document.getElementById("score-text");
        const testTitle = document.getElementById("title");
        choicesBox.innerHTML = "";
        let correctAnswers = 0;
        const questionVideoContainer = document.getElementById("questionVideoContainer");
       
       
        
        const totalQuestions = questions.length;
        const pointPerQuestion = 100 / totalQuestions;

        function UpdateTimer()
        {
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;

             const timerElement = document.getElementById("timer-box-text");

            timerElement.textContent = minutes + ":" + seconds.toString().padStart(2, "0");

            totalSeconds--;
            if(totalSeconds <= 300)
            {
                timerElement.style.color = "#a94442";
            }

            if(totalSeconds < 0)
            {
                clearInterval(timerInterval);
                FinishQuiz();
            }
        }
        

        function RenderQuestion()
        {
            const currentQuestion = questions[currentQuestionIndex];
            questionNumberElement.textContent = "Soru " + (currentQuestionIndex + 1) + " / " + totalQuestions;
            questionText.innerHTML = currentQuestion.questionText;
            questionImageContainer.innerHTML = "";
            questionVideoContainer.innerHTML = "";

            if
            (currentQuestion.questionImage && currentQuestion.questionImage.trim() !== "" )
            {
                const img = document.createElement("img");
                img.src = currentQuestion.questionImage;
                img.className = "question-image";
                questionImageContainer.appendChild(img);
            }
            if (currentQuestion.questionVideo && currentQuestion.questionVideo.trim() !== "")
            {
                const video = document.createElement("video");

                video.src = currentQuestion.questionVideo;
                video.className = "question-video";

                video.controls = true;
                video.preload = "metadata";

                questionVideoContainer.appendChild(video);
            }
            choicesBox.innerHTML = "";
            const letters = ["A", "B", "C", "D", "E"];
            currentQuestion.choices.forEach(function(choice, index)
            {
                const label = document.createElement("label");
                label.className = "choice-card";
                label.innerHTML = "";
                if(choice.text)
                {
                    const letter = document.createElement("div");
                    letter.className = "choice-letter";
                    letter.textContent = letters[index];
                    label.appendChild(letter);
                    
                    const textSpan = document.createElement("span");
                    textSpan.textContent = choice.text;
                    label.appendChild(textSpan);
                }

                if(choice.image)
                {
                    const img = document.createElement("img");
                    img.src = choice.image;
                    img.className = "choice-image";
                    label.appendChild(img);
                }
                choicesBox.appendChild(label);
                if (currentQuestion.answered)
                {
                    if (currentQuestion.selectedChoice === index)
                    {
                        if (choice.isCorrect)
                        {
                            label.style.backgroundColor = "#cfead6";
                            return;
                        }
                        else
                        {
                        label.style.backgroundColor = "#f6d3d7";
                        return;
                        }
                    }
                    else if (choice.isCorrect)
                    {
                        label.style.backgroundColor = "#cfead6";
                    }
                }

                label.addEventListener("click", function()
                {
                    if (currentQuestion.answered)
                        return;
                    currentQuestion.selectedChoice = index;
                    currentQuestion.answered = true;
                    const allLabels = document.querySelectorAll(".choice-card");

                    allLabels.forEach(label => {
                    label.classList.add("locked");
                    });

                
                    if (choice.isCorrect)
                    {
                        correctAnswers++;
                        label.style.backgroundColor = "#cfead6";
                        scoreCounterText.textContent =  correctAnswers + " soruyu doğru cevapladınız.";
                        currentQuestion.isAnsweredCorrectly = true;
                    }

                    else
                    {
                        label.style.backgroundColor = "#f6d3d7";
                        currentQuestion.isAnsweredCorrectly = false;
                        const allLabels = document.querySelectorAll(".choice-card");

                        currentQuestion.choices.forEach(function(choice, index)
                        {
                            if (choice.isCorrect)
                            {
                                allLabels[index].style.backgroundColor = "#cfead6";
                            }
                        });
                    }

                    const score = correctAnswers * pointPerQuestion;
                    scoreElement.textContent = Math.round(score) + ' Puan';
                });
            });

            const progressPercent = ((currentQuestionIndex + 1) / totalQuestions) * 100;
            progressFill.style.width = progressPercent + "%";

            const allButtons = document.querySelectorAll(".question-pill");

            allButtons.forEach(function(button, index)
            {
                const question = questions[index];

                if (index === currentQuestionIndex)
                {
                    button.style.backgroundColor = "#264298";
                    button.style.color = "white";
                }
                else if (question.isAnsweredCorrectly === true)
                {
                    button.style.backgroundColor = "#cfead6";
                    button.style.color = "black";
                }
                else if (question.isAnsweredCorrectly === false)
                {
                    button.style.backgroundColor = "#f6d3d7";
                    button.style.color = "black";
                }
                else
                {
                    button.style.backgroundColor = "#e5e9f8";
                    button.style.color = "black";
                }
            });   

            if(currentQuestionIndex === totalQuestions - 1)
            {
                btn_Next.textContent = "Bitir";
            }
            else
            {
                btn_Next.textContent = "İleri";
            }       
            if(currentQuestionIndex===0)
            {
                btn_Previous.classList.add("hidden");
            }
            else
            {
                btn_Previous.classList.remove("hidden");
            }
        }

        function FinishQuiz()
        {
            const score = correctAnswers * pointPerQuestion;

            const resultScore = document.getElementById("result-score");
            const resultMessage = document.getElementById("result-message");
            const modal = document.getElementById("resultModal");

            resultScore.textContent = "Puan: " + score + " / 100";

            if(score >= 70)
            {
                 confetti({
                    particleCount: 150,
                    spread: 90
                });
                resultMessage.textContent = "Tebrikler, sınavı geçtiniz!";
            }
            else
            {
                resultMessage.textContent = "Üzgünüz, sınavda başarısız oldunuz.";          
            }   

            modal.style.display = "flex";
        }
        
        btn_Next.addEventListener("click", IncreaseQuestionNumber);
        btn_Previous.addEventListener("click", DecreaseQuestionNumber);
        
        function IncreaseQuestionNumber()
        {
            if(currentQuestionIndex < totalQuestions - 1)
            {
                currentQuestionIndex++;
                RenderQuestion();
            }
            else
            {              
               Swal.fire({
                            title: "Testi Bitir?",
                            text: "Testi bitirmek istediğinize emin misiniz?",
                            icon: "question",
                            showCancelButton: true,
                            confirmButtonText: "Evet, Bitir",
                            cancelButtonText: "Vazgeç",
                            confirmButtonColor: "#264298",
                            cancelButtonColor: "#6c757d",
                            reverseButtons: true
                        }).then((result) => {
                                                 if (result.isConfirmed)
                                                {
                                                     FinishQuiz();
                                                }
                                            });
            }
                  
        }   

        function DecreaseQuestionNumber()
        {
            if(currentQuestionIndex > 0)
            {
                currentQuestionIndex--;
                RenderQuestion();
            }
        }
        

        questions.forEach(function(question, index)
        {
            const button = document.createElement("button");
            button.className = "question-pill";
            button.textContent = index + 1;

            questionsBox.appendChild(button);

            button.addEventListener("click", function()
            {
                currentQuestionIndex = index;
                RenderQuestion();
                if(window.innerWidth <= 768)
                {
                    pillDropdown.removeAttribute("open");
                }
            });
        });

       const pillDropdown = document.querySelector(".pill-dropdown");

        if(window.innerWidth <= 768)
        {
            pillDropdown.removeAttribute("open");
        }

        RenderQuestion();
        UpdateTimer();
        const timerInterval = setInterval(UpdateTimer, 1000);