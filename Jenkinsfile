pipeline {
    agent any

    environment {
        IMAGE_BASE = 'tomasmartin7/veterinaria-backend'
        DOCKER_CREDENTIALS_ID = 'docker-hub'
    }

    stages {
        stage('Detectar rama y tag') {
            steps {
                script {
                    def branch = env.GIT_BRANCH?.replace('origin/', '') ?: 'dev'
                    def tag = branch == 'main' ? 'latest' : branch
                    env.IMAGE_TAG = "${IMAGE_BASE}:${tag}"
                    echo "Construyendo imagen con tag: ${env.IMAGE_TAG}"
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
