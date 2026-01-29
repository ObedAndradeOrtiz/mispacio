import sys
import cv2
import numpy as np
from insightface.app import FaceAnalysis

def compare_faces(img1_path, img2_path):
    # Preparar modelo (solo se carga una vez)
    app = FaceAnalysis(name="buffalo_l")
    app.prepare(ctx_id=0, det_size=(640, 640))

    # Leer imágenes
    img1 = cv2.imread(img1_path)
    img2 = cv2.imread(img2_path)

    # Validar carga
    if img1 is None or img2 is None:
        print("0")
        return

    # Detectar rostros
    faces1 = app.get(img1)
    faces2 = app.get(img2)

    # Si no detecta rostro en alguna imagen → similitud = 0
    if len(faces1) == 0 or len(faces2) == 0:
        print("0")
        return

    # Tomar el primer rostro detectado
    emb1 = faces1[0].embedding
    emb2 = faces2[0].embedding

    # Validar embeddings
    if emb1 is None or emb2 is None:
        print("0")
        return

    # Normalizar vectores
    emb1 = emb1 / np.linalg.norm(emb1)
    emb2 = emb2 / np.linalg.norm(emb2)

    # Similitud coseno (entre -1 y 1)
    cos_sim = np.dot(emb1, emb2)

    # Convertirlo a porcentaje 0–100
    similarity = (cos_sim + 1) / 2 * 100

    # Limitar valores fuera de rango (por si acaso)
    similarity = max(0, min(100, similarity))

    print(similarity)


if __name__ == "__main__":
    if len(sys.argv) < 3:
        print("0")
        sys.exit(1)

    img1 = sys.argv[1]
    img2 = sys.argv[2]

    compare_faces(img1, img2)
