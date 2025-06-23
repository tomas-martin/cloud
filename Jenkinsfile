pipeline {
    agent any

    environment {
        IMAGE_BASE = 'tomasmartin7/veterinaria-backend'
        DOCKER_CREDENTIALS_ID = 'docker-hub'
    }

    options {
        skipDefaultCheckout(true)
    }

    stages {
        stage('Validar rama') {
            steps {
                checkout scm
                script {
                    def rawBranch = sh(script: "git symbolic-ref --short HEAD || git name-rev --name-only HEAD", returnStdout: true).trim()
                    def gitBranch = rawBranch.tokenize('/').last()

                    echo "🔍 DEBUG: Rama Git detectada (raw) = ${rawBranch}"
                    echo "🔍 DEBUG: Rama Git detectada (limpia) = ${gitBranch}"
                    echo "🔍 DEBUG: JOB_NAME = ${env.JOB_NAME}"

                    if ((env.JOB_NAME == 'ci-dev' && gitBranch != 'dev') ||
                        (env.JOB_NAME == 'ci-staging' && gitBranch != 'staging') ||
                        (env.JOB_NAME == 'ci-prod' && gitBranch != 'main')) {
                        echo "🚫 Esta rama (${gitBranch}) no corresponde al job ${env.JOB_NAME}. Deteniendo ejecución."
                        currentBuild.result = 'NOT_BUILT'
                        error("Build cancelado por protección de ambiente")
                    }

                    def tag = (gitBranch == 'main') ? 'latest' : gitBranch
                    env.IMAGE_TAG = "${IMAGE_BASE}:${tag}"
                    echo "✅ Rama válida: ${gitBranch}. Tag a usar: ${env.IMAGE_TAG}"
                }
            }
        }

        stage('Build Docker') {
            steps {
                sh "docker build -t $IMAGE_TAG ./backend"
            }
        }

        stage('Push a Docker Hub') {
            steps {
                withCredentials([usernamePassword(credentialsId: DOCKER_CREDENTIALS_ID,
                                                 usernameVariable: 'DOCKER_USER',
                                                 passwordVariable: 'DOCKER_PASS')]) {
                    sh '''
                        echo "$DOCKER_PASS" | docker login -u "$DOCKER_USER" --password-stdin
                        docker push $IMAGE_TAG
                    '''
                }
            }
        }

        stage('Notificar a Render (CD)') {
            when {
                anyOf {
                    expression { env.JOB_NAME == 'ci-dev' }
                    expression { env.JOB_NAME == 'ci-staging' }
                    expression { env.JOB_NAME == 'ci-prod' }
                }
            }
            steps {
                script {
                    def renderWebhook = ''

                    if (env.JOB_NAME == 'ci-dev') {
                        renderWebhook = 'https://api.render.com/deploy/srv-d1ccfjp5pdvs73en0nfg?key=4XaFhIxM3Uw'
                    } else if (env.JOB_NAME == 'ci-staging') {
                        renderWebhook = 'https://api.render.com/deploy/srv-d1cchq95pdvs73en2ljg?key=3haytWrwBuo'
                    } else if (env.JOB_NAME == 'ci-prod') {
                        renderWebhook = 'https://api.render.com/deploy/srv-d1a17fumcj7s73f24n40?key=DPabSeHE7e4'
                    }

                    echo "🚀 Notificando a Render: ${renderWebhook}"
                    sh "curl -X POST ${renderWebhook}"
                }
            }
        }
    }
}
