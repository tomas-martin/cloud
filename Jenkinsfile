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
                script {
                    def branch = env.BRANCH_NAME ?: 'dev'

                    // Validación por job y rama
                    if ((env.JOB_NAME == 'ci-dev' && branch != 'dev') ||
                        (env.JOB_NAME == 'ci-staging' && branch != 'staging') ||
                        (env.JOB_NAME == 'ci-prod' && branch != 'main')) {
                        echo "🚫 Esta rama (${branch}) no corresponde al job ${env.JOB_NAME}. Deteniendo ejecución."
                        currentBuild.result = 'NOT_BUILT'
                        error("Build cancelado por protección de ambiente")
                    }

                    // Seteo del tag
                    def tag = (branch == 'main') ? 'latest' : branch
                    env.IMAGE_TAG = "${IMAGE_BASE}:${tag}"
                    echo "✅ Rama válida: ${branch}. Tag a usar: ${env.IMAGE_TAG}"

                    // Checkout del repo
                    checkout scm
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
                expression { env.JOB_NAME == 'ci-prod' }
            }
            steps {
                sh 'curl -X POST https://api.render.com/deploy/srv-d1a17fumcj7s73f24n40?key=DPabSeHE7e4'
            }
        }
    }
}
