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
                    def branch = env.GIT_BRANCH?.replace('origin/', '') ?: 'dev'

                    // Cortar la ejecución si la rama no coincide con el job
                    if ((env.JOB_NAME == 'ci-dev' && branch != 'dev') ||
                        (env.JOB_NAME == 'ci-staging' && branch != 'staging') ||
                        (env.JOB_NAME == 'ci-prod' && branch != 'main')) {
                        echo "🚫 Esta rama (${branch}) no corresponde al job ${env.JOB_NAME}. Deteniendo ejecución."
                        currentBuild.result = 'NOT_BUILT'
                        error("Build cancelado por protección de ambiente")
                    }

                    // Si coincide, setear el tag
                    env.IMAGE_TAG = "${IMAGE_BASE}:${branch == 'main' ? 'latest' : branch}"
                    echo "✅ Rama válida: ${branch}. Tag a usar: ${env.IMAGE_TAG}"

                    // Hacemos el checkout
                    checkout scm
                }
            }
        }

        stage('Build Docker') {
            steps {
                sh "docker build -t $IMAGE_TAG ."
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
    }
}
